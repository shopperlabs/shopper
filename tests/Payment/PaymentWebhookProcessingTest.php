<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Event;
use Shopper\Core\Contracts\StockReserver;
use Shopper\Core\Enum\OrderStatus;
use Shopper\Core\Enum\PaymentStatus;
use Shopper\Core\Enum\ShippingStatus;
use Shopper\Core\Events\Orders\OrderPaid;
use Shopper\Core\Models\Inventory;
use Shopper\Core\Models\Order;
use Shopper\Core\Models\OrderItem;
use Shopper\Core\Models\PaymentMethod;
use Shopper\Core\Models\Product;
use Shopper\Payment\Actions\IngestPaymentEvent;
use Shopper\Payment\DataTransferObjects\WebhookResult;
use Shopper\Payment\Enum\TransactionStatus;
use Shopper\Payment\Enum\TransactionType;
use Shopper\Payment\Enum\WebhookAction;
use Shopper\Payment\Events\PaymentFailed;
use Shopper\Payment\Models\PaymentTransaction;
use Tests\Core\Stubs\User;

uses(Tests\Core\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();

    $this->method = PaymentMethod::factory()->create(['driver' => 'fake']);
    $this->order = Order::factory()->create([
        'payment_method_id' => $this->method->id,
        'price_amount' => 5000,
        'currency_code' => 'USD',
        'status' => OrderStatus::New,
        'payment_status' => PaymentStatus::Pending,
        'shipping_status' => ShippingStatus::Unfulfilled,
    ]);

    // The intent recorded at checkout is what ties the webhook back to the order.
    PaymentTransaction::query()->create([
        'order_id' => $this->order->id,
        'payment_method_id' => $this->method->id,
        'driver' => 'fake',
        'type' => TransactionType::Initiate,
        'status' => TransactionStatus::Pending,
        'amount' => 5000,
        'currency_code' => 'USD',
        'reference' => 'pi_123',
    ]);

    $this->ingest = resolve(IngestPaymentEvent::class);
});

describe('PaymentWebhookProcessingTest', function (): void {
    it('advances the order to `Paid` on a captured event', function (): void {
        $this->ingest->execute('fake', new WebhookResult(
            action: WebhookAction::Captured,
            reference: 'pi_123',
            amount: 5000,
            eventId: 'evt_1',
        ));

        $capture = PaymentTransaction::query()
            ->where('order_id', $this->order->id)
            ->where('type', TransactionType::Capture)
            ->first();

        expect($this->order->refresh()->payment_status)->toBe(PaymentStatus::Paid)
            ->and($capture)->not->toBeNull()
            ->and($capture->status)->toBe(TransactionStatus::Success)
            ->and($capture->amount)->toBe(5000);
    });

    it('advances the order to `Authorized` on an authorized event', function (): void {
        $this->ingest->execute('fake', new WebhookResult(
            action: WebhookAction::Authorized,
            reference: 'pi_123',
            amount: 5000,
            eventId: 'evt_2',
        ));

        expect($this->order->refresh()->payment_status)->toBe(PaymentStatus::Authorized);
    });

    it('marks the order `PartiallyRefunded` then `Refunded` as refunds accumulate', function (): void {
        $this->ingest->execute('fake', new WebhookResult(
            action: WebhookAction::Captured,
            reference: 'pi_123',
            amount: 5000,
            eventId: 'evt_capture',
        ));

        $this->ingest->execute('fake', new WebhookResult(
            action: WebhookAction::Refunded,
            reference: 'pi_123',
            amount: 2000,
            eventId: 'evt_3',
        ));

        expect($this->order->refresh()->payment_status)->toBe(PaymentStatus::PartiallyRefunded);

        $this->ingest->execute('fake', new WebhookResult(
            action: WebhookAction::Refunded,
            reference: 'pi_123',
            amount: 3000,
            eventId: 'evt_4',
        ));

        expect($this->order->refresh()->payment_status)->toBe(PaymentStatus::Refunded);
    });

    it('records a failed attempt and dispatches `PaymentFailed` without cancelling the order', function (): void {
        Event::fake([PaymentFailed::class]);

        $this->ingest->execute('fake', new WebhookResult(
            action: WebhookAction::Failed,
            reference: 'pi_123',
            amount: 5000,
            data: ['failure_message' => 'card_declined'],
            eventId: 'evt_5',
        ));

        expect($this->order->refresh()->status)->toBe(OrderStatus::New)
            ->and($this->order->payment_status)->toBe(PaymentStatus::Pending)
            ->and(PaymentTransaction::query()->where('order_id', $this->order->id)->where('type', TransactionType::Capture)->where('status', TransactionStatus::Failed)->count())->toBe(1);
        Event::assertDispatched(PaymentFailed::class);
    });

    it('keeps the stock reserved on a failed attempt so the customer can retry', function (): void {
        $inventory = Inventory::factory()->create(['is_default' => true, 'priority' => 0]);
        $product = Product::factory()->standard()->create();
        $product->mutateStock($inventory->id, 10, event: 'Initial');

        OrderItem::factory()->create([
            'order_id' => $this->order->id,
            'product_type' => $product->getMorphClass(),
            'product_id' => $product->id,
            'quantity' => 3,
        ]);

        resolve(StockReserver::class)->reserve($product, 3, $this->order, $this->user->id);
        expect($product->getStock())->toBe(7);

        $this->ingest->execute('fake', new WebhookResult(
            action: WebhookAction::Failed,
            reference: 'pi_123',
            amount: 5000,
            eventId: 'evt_6',
        ));

        expect($this->order->refresh()->status)->toBe(OrderStatus::New)
            ->and($product->getStock())->toBe(7);
    });

    it('collapses a redelivered refund onto the refund id', function (): void {
        $this->ingest->execute('fake', new WebhookResult(
            action: WebhookAction::Captured,
            reference: 'pi_123',
            amount: 5000,
            eventId: 'evt_refund_capture',
        ));

        foreach (['evt_refund_a', 'evt_refund_b'] as $eventId) {
            $this->ingest->execute('fake', new WebhookResult(
                action: WebhookAction::Refunded,
                reference: 'pi_123',
                amount: 2000,
                data: ['refund_id' => 're_1'],
                eventId: $eventId,
            ));
        }

        expect($this->order->refresh()->payment_status)->toBe(PaymentStatus::PartiallyRefunded)
            ->and((int) PaymentTransaction::query()->where('order_id', $this->order->id)->where('type', TransactionType::Refund)->sum('amount'))->toBe(2000);
    });

    it('does not count an admin refund twice when its webhook confirms it', function (): void {
        $this->ingest->execute('fake', new WebhookResult(
            action: WebhookAction::Captured,
            reference: 'pi_123',
            amount: 5000,
            eventId: 'evt_admin_capture',
        ));

        PaymentTransaction::factory()->refund()->create([
            'order_id' => $this->order->id,
            'payment_method_id' => $this->method->id,
            'driver' => 'fake',
            'amount' => 2000,
            'reference' => 're_admin',
        ]);

        $this->ingest->execute('fake', new WebhookResult(
            action: WebhookAction::Refunded,
            reference: 'pi_123',
            amount: 2000,
            data: ['refund_id' => 're_admin'],
            eventId: 'evt_admin_refund',
        ));

        expect(PaymentTransaction::query()->where('order_id', $this->order->id)->where('type', TransactionType::Refund)->count())->toBe(1);
    });

    it('keeps a refunded order refunded when a late captured event arrives', function (): void {
        $this->ingest->execute('fake', new WebhookResult(
            action: WebhookAction::Captured,
            reference: 'pi_123',
            amount: 5000,
            eventId: 'evt_late_1',
        ));

        $this->ingest->execute('fake', new WebhookResult(
            action: WebhookAction::Refunded,
            reference: 'pi_123',
            amount: 5000,
            eventId: 'evt_late_2',
        ));

        expect($this->order->refresh()->payment_status)->toBe(PaymentStatus::Refunded);

        $this->ingest->execute('fake', new WebhookResult(
            action: WebhookAction::Captured,
            reference: 'pi_123',
            amount: 5000,
            eventId: 'evt_late_3',
        ));

        expect($this->order->refresh()->payment_status)->toBe(PaymentStatus::Refunded)
            ->and(PaymentTransaction::query()->where('order_id', $this->order->id)->where('type', TransactionType::Capture)->count())->toBe(1);
    });

    it('keeps a paid order paid when a late failed event arrives', function (): void {
        $inventory = Inventory::factory()->create(['is_default' => true, 'priority' => 0]);
        $product = Product::factory()->standard()->create();
        $product->mutateStock($inventory->id, 10, event: 'Initial');

        OrderItem::factory()->create([
            'order_id' => $this->order->id,
            'product_type' => $product->getMorphClass(),
            'product_id' => $product->id,
            'quantity' => 3,
        ]);

        resolve(StockReserver::class)->reserve($product, 3, $this->order, $this->user->id);

        $this->ingest->execute('fake', new WebhookResult(
            action: WebhookAction::Captured,
            reference: 'pi_123',
            amount: 5000,
            eventId: 'evt_ooo_1',
        ));

        $this->ingest->execute('fake', new WebhookResult(
            action: WebhookAction::Failed,
            reference: 'pi_123',
            amount: 5000,
            data: ['failure_message' => 'late failure'],
            eventId: 'evt_ooo_2',
        ));

        expect($this->order->refresh()->payment_status)->toBe(PaymentStatus::Paid)
            ->and($this->order->status)->not->toBe(OrderStatus::Cancelled)
            ->and($product->getStock())->toBe(7);
    });

    it('dispatches `OrderPaid` when a captured webhook marks the order paid', function (): void {
        Event::fake([OrderPaid::class]);

        $this->ingest->execute('fake', new WebhookResult(
            action: WebhookAction::Captured,
            reference: 'pi_123',
            amount: 5000,
            eventId: 'evt_paid_event',
        ));

        Event::assertDispatched(
            OrderPaid::class,
            fn (OrderPaid $event): bool => $event->order->getKey() === $this->order->getKey(),
        );
    });

    it('does nothing when no order matches the reference', function (): void {
        $this->ingest->execute('fake', new WebhookResult(
            action: WebhookAction::Captured,
            reference: 'pi_unknown',
            amount: 5000,
            eventId: 'evt_7',
        ));

        expect($this->order->refresh()->payment_status)->toBe(PaymentStatus::Pending)
            ->and(PaymentTransaction::query()->where('type', TransactionType::Capture)->count())->toBe(0);
    });

    it('ignores an ignored webhook result', function (): void {
        $this->ingest->execute('fake', WebhookResult::ignored());

        expect($this->order->refresh()->payment_status)->toBe(PaymentStatus::Pending);
    });
})->group('payment', 'webhooks');
