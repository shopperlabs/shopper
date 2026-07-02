<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Event;
use Shopper\Core\Enum\PaymentStatus;
use Shopper\Core\Events\Orders\OrderPaid;
use Shopper\Core\Exceptions\InvalidPaymentStatusTransitionException;
use Shopper\Core\Models\Order;

uses(Tests\Core\TestCase::class);

describe('PaymentStatus transitions', function (): void {
    it('allows a `Pending` payment to be authorized, paid, or voided', function (): void {
        expect(PaymentStatus::Pending->canTransitionTo(PaymentStatus::Authorized))->toBeTrue()
            ->and(PaymentStatus::Pending->canTransitionTo(PaymentStatus::Paid))->toBeTrue()
            ->and(PaymentStatus::Pending->canTransitionTo(PaymentStatus::Voided))->toBeTrue()
            ->and(PaymentStatus::Pending->canTransitionTo(PaymentStatus::Refunded))->toBeFalse();
    });

    it('only allows a `Paid` payment to move toward refunds', function (): void {
        expect(PaymentStatus::Paid->canTransitionTo(PaymentStatus::PartiallyRefunded))->toBeTrue()
            ->and(PaymentStatus::Paid->canTransitionTo(PaymentStatus::Refunded))->toBeTrue()
            ->and(PaymentStatus::Paid->canTransitionTo(PaymentStatus::Paid))->toBeFalse()
            ->and(PaymentStatus::Paid->canTransitionTo(PaymentStatus::Pending))->toBeFalse()
            ->and(PaymentStatus::Paid->canTransitionTo(PaymentStatus::Voided))->toBeFalse();
    });

    it('allows successive partial refunds', function (): void {
        expect(PaymentStatus::PartiallyRefunded->canTransitionTo(PaymentStatus::PartiallyRefunded))->toBeTrue()
            ->and(PaymentStatus::PartiallyRefunded->canTransitionTo(PaymentStatus::Refunded))->toBeTrue()
            ->and(PaymentStatus::PartiallyRefunded->canTransitionTo(PaymentStatus::Paid))->toBeFalse();
    });

    it('treats `Refunded` and `Voided` as terminal states', function (): void {
        expect(PaymentStatus::Refunded->canTransitionTo(PaymentStatus::Paid))->toBeFalse()
            ->and(PaymentStatus::Refunded->canTransitionTo(PaymentStatus::Pending))->toBeFalse()
            ->and(PaymentStatus::Voided->canTransitionTo(PaymentStatus::Paid))->toBeFalse()
            ->and(PaymentStatus::Voided->canTransitionTo(PaymentStatus::Pending))->toBeFalse();
    });
});

describe('Order::transitionPaymentTo', function (): void {
    it('persists a valid transition', function (): void {
        $order = Order::factory()->create(['payment_status' => PaymentStatus::Pending]);

        $order->transitionPaymentTo(PaymentStatus::Authorized);

        expect($order->refresh()->payment_status)->toBe(PaymentStatus::Authorized);
    });

    it('throws on an invalid transition and leaves the status untouched', function (): void {
        $order = Order::factory()->create(['payment_status' => PaymentStatus::Refunded]);

        expect(fn () => $order->transitionPaymentTo(PaymentStatus::Paid))
            ->toThrow(InvalidPaymentStatusTransitionException::class);

        expect($order->refresh()->payment_status)->toBe(PaymentStatus::Refunded);
    });

    it('dispatches `OrderPaid` when transitioning to `Paid`', function (): void {
        Event::fake([OrderPaid::class]);

        $order = Order::factory()->create(['payment_status' => PaymentStatus::Pending]);

        $order->transitionPaymentTo(PaymentStatus::Paid);

        Event::assertDispatched(
            OrderPaid::class,
            fn (OrderPaid $event): bool => $event->order->getKey() === $order->getKey(),
        );
    });

    it('does not dispatch `OrderPaid` for other transitions', function (): void {
        Event::fake([OrderPaid::class]);

        $order = Order::factory()->create(['payment_status' => PaymentStatus::Pending]);

        $order->transitionPaymentTo(PaymentStatus::Authorized);

        Event::assertNotDispatched(OrderPaid::class);
    });
});
