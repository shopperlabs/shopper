<?php

declare(strict_types=1);

use Shopper\Core\Enum\OrderStatus;
use Shopper\Core\Enum\PaymentStatus;
use Shopper\Core\Enum\ShippingStatus;
use Shopper\Core\Models\Inventory;
use Shopper\Core\Models\Order;
use Shopper\Core\Models\OrderItem;
use Shopper\Core\Models\PaymentMethod;
use Shopper\Core\Models\Product;
use Shopper\Core\Contracts\StockReserver;
use Shopper\Payment\DataTransferObjects\WebhookResult;
use Shopper\Payment\Enum\TransactionStatus;
use Shopper\Payment\Enum\TransactionType;
use Shopper\Payment\Events\PaymentFailed;
use Shopper\Payment\Models\PaymentTransaction;
use Shopper\Payment\Services\PaymentProcessingService;
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

    $this->service = resolve(PaymentProcessingService::class);
});

describe('PaymentWebhookProcessingTest', function (): void {
    it('advances the order to `Paid` on a captured event', function (): void {
        $this->service->processWebhook('fake', new WebhookResult(
            action: 'captured',
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
        $this->service->processWebhook('fake', new WebhookResult(
            action: 'authorized',
            reference: 'pi_123',
            amount: 5000,
            eventId: 'evt_2',
        ));

        expect($this->order->refresh()->payment_status)->toBe(PaymentStatus::Authorized);
    });

    it('marks the order `PartiallyRefunded` then `Refunded` as refunds accumulate', function (): void {
        $this->service->processWebhook('fake', new WebhookResult(
            action: 'refunded',
            reference: 'pi_123',
            amount: 2000,
            eventId: 'evt_3',
        ));

        expect($this->order->refresh()->payment_status)->toBe(PaymentStatus::PartiallyRefunded);

        $this->service->processWebhook('fake', new WebhookResult(
            action: 'refunded',
            reference: 'pi_123',
            amount: 3000,
            eventId: 'evt_4',
        ));

        expect($this->order->refresh()->payment_status)->toBe(PaymentStatus::Refunded);
    });

    it('cancels the order and dispatches `PaymentFailed` on a failed event', function (): void {
        Event::fake([PaymentFailed::class]);

        $this->service->processWebhook('fake', new WebhookResult(
            action: 'failed',
            reference: 'pi_123',
            amount: 5000,
            data: ['failure_message' => 'card_declined'],
            eventId: 'evt_5',
        ));

        expect($this->order->refresh()->status)->toBe(OrderStatus::Cancelled);
        Event::assertDispatched(PaymentFailed::class);
    });

    it('restores reserved stock when a payment fails', function (): void {
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

        $this->service->processWebhook('fake', new WebhookResult(
            action: 'failed',
            reference: 'pi_123',
            amount: 5000,
            eventId: 'evt_6',
        ));

        expect($this->order->refresh()->status)->toBe(OrderStatus::Cancelled)
            ->and($product->getStock())->toBe(10);
    });

    it('does nothing when no order matches the reference', function (): void {
        $this->service->processWebhook('fake', new WebhookResult(
            action: 'captured',
            reference: 'pi_unknown',
            amount: 5000,
            eventId: 'evt_7',
        ));

        expect($this->order->refresh()->payment_status)->toBe(PaymentStatus::Pending)
            ->and(PaymentTransaction::query()->where('type', TransactionType::Capture)->count())->toBe(0);
    });

    it('ignores an ignored webhook result', function (): void {
        $this->service->processWebhook('fake', WebhookResult::ignored());

        expect($this->order->refresh()->payment_status)->toBe(PaymentStatus::Pending);
    });
})->group('payment', 'webhooks');
