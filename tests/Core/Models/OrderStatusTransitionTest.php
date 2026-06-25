<?php

declare(strict_types=1);

use Shopper\Core\Enum\OrderStatus;
use Shopper\Core\Exceptions\InvalidOrderStatusTransitionException;
use Shopper\Core\Models\Order;

uses(Tests\Core\TestCase::class);

describe('OrderStatus transitions', function (): void {
    it('allows a `New` order to move forward, cancel, or archive', function (): void {
        expect(OrderStatus::New->canTransitionTo(OrderStatus::Processing))->toBeTrue()
            ->and(OrderStatus::New->canTransitionTo(OrderStatus::Completed))->toBeTrue()
            ->and(OrderStatus::New->canTransitionTo(OrderStatus::Cancelled))->toBeTrue()
            ->and(OrderStatus::New->canTransitionTo(OrderStatus::Archived))->toBeTrue();
    });

    it('only allows a `Cancelled` order to be archived', function (): void {
        expect(OrderStatus::Cancelled->canTransitionTo(OrderStatus::Archived))->toBeTrue()
            ->and(OrderStatus::Cancelled->canTransitionTo(OrderStatus::Completed))->toBeFalse()
            ->and(OrderStatus::Cancelled->canTransitionTo(OrderStatus::Processing))->toBeFalse()
            ->and(OrderStatus::Cancelled->canTransitionTo(OrderStatus::New))->toBeFalse();
    });

    it('treats `Archived` as a terminal state', function (): void {
        expect(OrderStatus::Archived->canTransitionTo(OrderStatus::New))->toBeFalse()
            ->and(OrderStatus::Archived->canTransitionTo(OrderStatus::Processing))->toBeFalse()
            ->and(OrderStatus::Archived->canTransitionTo(OrderStatus::Cancelled))->toBeFalse();
    });
});

describe('Order::transitionTo', function (): void {
    it('persists a valid transition', function (): void {
        $order = Order::factory()->create(['status' => OrderStatus::New]);

        $order->transitionTo(OrderStatus::Processing);

        expect($order->refresh()->status)->toBe(OrderStatus::Processing);
    });

    it('stamps `cancelled_at` when cancelling', function (): void {
        $order = Order::factory()->create(['status' => OrderStatus::Processing, 'cancelled_at' => null]);

        $order->transitionTo(OrderStatus::Cancelled);

        expect($order->refresh()->status)->toBe(OrderStatus::Cancelled)
            ->and($order->cancelled_at)->not->toBeNull();
    });

    it('stamps `archived_at` when archiving', function (): void {
        $order = Order::factory()->create(['status' => OrderStatus::Processing, 'archived_at' => null]);

        $order->transitionTo(OrderStatus::Archived);

        expect($order->refresh()->status)->toBe(OrderStatus::Archived)
            ->and($order->archived_at)->not->toBeNull();
    });

    it('throws when the transition is invalid', function (): void {
        $order = Order::factory()->create(['status' => OrderStatus::Cancelled]);

        expect(fn () => $order->transitionTo(OrderStatus::Completed))
            ->toThrow(InvalidOrderStatusTransitionException::class);

        expect($order->refresh()->status)->toBe(OrderStatus::Cancelled);
    });

    it('delegates `canTransitionTo` to the current status', function (): void {
        $order = Order::factory()->create(['status' => OrderStatus::Archived]);

        expect($order->canTransitionTo(OrderStatus::New))->toBeFalse()
            ->and($order->canTransitionTo(OrderStatus::Cancelled))->toBeFalse();
    });
})->group('orders');
