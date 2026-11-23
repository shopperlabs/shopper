<?php

declare(strict_types=1);

use Illuminate\Support\Str;
use Livewire\Livewire;
use Shopper\Core\Enum\OrderStatus;
use Shopper\Core\Enum\PaymentStatus;
use Shopper\Core\Models\Order;
use Shopper\Core\Models\PaymentMethod;
use Shopper\Livewire\Pages\Order\Detail;
use Shopper\Payment\Enum\TransactionStatus;
use Shopper\Payment\Enum\TransactionType;
use Shopper\Payment\Facades\Payment;
use Shopper\Payment\Models\PaymentTransaction;
use Shopper\Payment\Services\PaymentProcessingService;
use Tests\Api\Stubs\FakePaymentDriver;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->user->givePermissionTo(['orders.read', 'orders.edit']);
    $this->actingAs($this->user);

    $this->driver = new FakePaymentDriver;
    $driver = $this->driver;
    Payment::extend('fake', fn (): FakePaymentDriver => $driver);

    $this->order = Order::factory()->hasItems(1)->create([
        'number' => 'ORD-1001',
        'status' => OrderStatus::Processing,
        'payment_status' => PaymentStatus::Paid,
        'payment_method_id' => PaymentMethod::factory()->create(['driver' => 'fake'])->id,
        'price_amount' => 5000,
        'currency_code' => 'USD',
    ]);

    PaymentTransaction::factory()->create([
        'order_id' => $this->order->id,
        'driver' => 'fake',
        'reference' => 'pi_123',
        'amount' => 5000,
    ]);
});

describe('refundPayment action', function (): void {
    it('refunds part of the payment with the reason the staff picked', function (): void {
        Livewire::test(Detail::class, ['order' => $this->order])
            ->callAction('refundPayment', data: ['amount' => 30, 'reason' => 'duplicate'])
            ->assertNotified(__('shopper::pages/orders.notifications.refunded'));

        $refund = PaymentTransaction::query()->where('type', TransactionType::Refund)->sole();

        expect($this->order->fresh()->payment_status)->toBe(PaymentStatus::PartiallyRefunded)
            ->and($refund->amount)->toBe(3000)
            ->and($refund->user_id)->toBe($this->user->id)
            ->and($refund->metadata)->toBe(['reason' => 'duplicate'])
            ->and($this->driver->refunds[0]['reason'])->toBe('duplicate');
    });

    it('refunds the remaining amount against the payment, never a previous refund id', function (): void {
        $this->travel(1)->hour();

        Livewire::test(Detail::class, ['order' => $this->order])
            ->callAction('refundPayment', data: ['amount' => 30, 'reason' => 'requested_by_customer'])
            ->assertNotified(__('shopper::pages/orders.notifications.refunded'))
            ->callAction('refundPayment', data: ['amount' => 20, 'reason' => 'requested_by_customer'])
            ->assertNotified(__('shopper::pages/orders.notifications.refunded'))
            ->assertActionHidden('refundPayment');

        expect(collect($this->driver->refunds)->pluck('reference')->all())->toBe(['pi_123', 'pi_123'])
            ->and($this->order->fresh()->payment_status)->toBe(PaymentStatus::Refunded);
    });

    it('rejects an amount above what remains refundable', function (): void {
        Livewire::test(Detail::class, ['order' => $this->order])
            ->callAction('refundPayment', data: ['amount' => 50.01, 'reason' => 'requested_by_customer'])
            ->assertHasFormErrors(['amount' => 'max']);

        expect($this->driver->refunds)->toBeEmpty()
            ->and($this->order->fresh()->payment_status)->toBe(PaymentStatus::Paid);
    });

    it('records an offline refund for a manual payment', function (): void {
        $order = Order::factory()->hasItems(1)->create([
            'number' => 'ORD-1002',
            'status' => OrderStatus::Completed,
            'payment_status' => PaymentStatus::Paid,
            'payment_method_id' => PaymentMethod::factory()->create(['driver' => 'manual'])->id,
            'price_amount' => 8000,
            'currency_code' => 'USD',
        ]);

        Livewire::test(Detail::class, ['order' => $order])
            ->assertActionVisible('refundPayment')
            ->callAction('refundPayment', data: ['amount' => 80, 'reason' => 'requested_by_customer'])
            ->assertNotified(__('shopper::pages/orders.notifications.refunded'));

        $refund = PaymentTransaction::query()->where('order_id', $order->id)->sole();

        expect($order->fresh()->payment_status)->toBe(PaymentStatus::Refunded)
            ->and($refund->type)->toBe(TransactionType::Refund)
            ->and($refund->status)->toBe(TransactionStatus::Success)
            ->and($refund->driver)->toBe('manual')
            ->and($refund->amount)->toBe(8000);
    });

    it('keeps a pending refund out of the refundable amount until the provider settles it', function (): void {
        $this->driver->refundStatus = 'pending';

        Livewire::test(Detail::class, ['order' => $this->order])
            ->callAction('refundPayment', data: ['amount' => 20, 'reason' => 'requested_by_customer'])
            ->assertNotified(__('shopper::pages/orders.notifications.refund_pending'))
            ->assertActionVisible('refundPayment');

        expect(PaymentTransaction::query()->where('type', TransactionType::Refund)->sole()->status)->toBe(TransactionStatus::Pending)
            ->and($this->order->fresh()->payment_status)->toBe(PaymentStatus::Paid)
            ->and(resolve(PaymentProcessingService::class)->refundableAmount($this->order))->toBe(3000);
    });

    it('tells the staff when the provider declines the refund', function (): void {
        $this->driver->failRefund = true;

        Livewire::test(Detail::class, ['order' => $this->order])
            ->callAction('refundPayment', data: ['amount' => 20, 'reason' => 'requested_by_customer'])
            ->assertNotified(__('shopper::pages/orders.notifications.refund_failed'));

        expect($this->order->fresh()->payment_status)->toBe(PaymentStatus::Paid)
            ->and(resolve(PaymentProcessingService::class)->refundableAmount($this->order))->toBe(5000);
    });

    it('refunds once when the same modal is submitted twice', function (): void {
        $data = ['amount' => 20, 'reason' => 'requested_by_customer', 'idempotency_key' => Str::uuid()->toString()];

        Livewire::test(Detail::class, ['order' => $this->order])
            ->callAction('refundPayment', data: $data)
            ->assertNotified(__('shopper::pages/orders.notifications.refunded'))
            ->callAction('refundPayment', data: $data);

        expect($this->driver->refunds)->toHaveCount(1)
            ->and(PaymentTransaction::query()->where('type', TransactionType::Refund)->count())->toBe(1);
    });

    it('keeps the modal open with the same key when the provider times out', function (): void {
        $this->driver->timeOutNextRefund = true;

        Livewire::test(Detail::class, ['order' => $this->order])
            ->mountAction('refundPayment')
            ->fillForm(['amount' => 20, 'reason' => 'requested_by_customer'])
            ->callMountedAction()
            ->assertNotified(__('shopper::pages/orders.notifications.refund_failed'))
            ->assertActionMounted('refundPayment')
            ->callMountedAction()
            ->assertNotified(__('shopper::pages/orders.notifications.refunded'));

        expect(collect($this->driver->refunds)->pluck('context.idempotency_key')->unique())->toHaveCount(1)
            ->and(PaymentTransaction::query()->where('type', TransactionType::Refund)->sole()->amount)->toBe(2000);
    });

    it('is hidden when nothing is left to refund', function (PaymentStatus $status): void {
        $this->order->update(['payment_status' => $status]);

        Livewire::test(Detail::class, ['order' => $this->order])
            ->assertActionHidden('refundPayment');
    })->with([
        'pending' => PaymentStatus::Pending,
        'authorized' => PaymentStatus::Authorized,
        'refunded' => PaymentStatus::Refunded,
    ]);

    it('is hidden for users without `orders.edit`', function (): void {
        $this->user->revokePermissionTo('orders.edit');

        Livewire::test(Detail::class, ['order' => $this->order])
            ->assertActionHidden('refundPayment');
    });
})->group('livewire', 'orders', 'payment');
