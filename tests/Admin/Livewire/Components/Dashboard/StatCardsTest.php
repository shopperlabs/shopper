<?php

declare(strict_types=1);

use Carbon\Carbon;
use Livewire\Livewire;
use Shopper\Core\Enum\OrderRefundStatus;
use Shopper\Core\Enum\OrderStatus;
use Shopper\Core\Enum\PaymentStatus;
use Shopper\Core\Enum\ShippingStatus;
use Shopper\Core\Models\Order;
use Shopper\Core\Models\OrderRefund;
use Shopper\Livewire\Components\Dashboard\StatCards;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    Carbon::setTestNow('2026-07-15 12:00:00');

    $this->actingAs(User::factory()->create(), config('shopper.auth.guard'));
});

afterEach(fn () => Carbon::setTestNow());

function createDashboardOrder(array $attributes = []): Order
{
    return Order::factory()->create([
        'status' => OrderStatus::Completed,
        'payment_status' => PaymentStatus::Paid,
        'shipping_status' => ShippingStatus::Delivered,
        'currency_code' => shopper_currency(),
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
        ...$attributes,
    ]);
}

describe(StatCards::class, function (): void {
    it('keeps refunded orders in revenue and deducts settled refund amounts', function (): void {
        $currency = shopper_currency();

        createDashboardOrder(['price_amount' => 10000]);

        $partiallyRefunded = createDashboardOrder([
            'price_amount' => 5000,
            'payment_status' => PaymentStatus::PartiallyRefunded,
        ]);

        OrderRefund::factory()->create([
            'order_id' => $partiallyRefunded->id,
            'amount' => 2000,
            'currency' => $currency,
            'status' => OrderRefundStatus::Partial_Refund,
        ]);

        $awaitingRefund = createDashboardOrder(['price_amount' => 3000]);

        OrderRefund::factory()->create([
            'order_id' => $awaitingRefund->id,
            'amount' => 999,
            'currency' => $currency,
            'status' => OrderRefundStatus::Awaiting,
        ]);

        createDashboardOrder([
            'price_amount' => 7000,
            'payment_status' => PaymentStatus::Voided,
        ]);

        $cards = Livewire::test(StatCards::class)->instance()->cards;

        expect($cards[0]['value'])->toBe(shopper_money_format(10000 + 5000 - 2000 + 3000, $currency));
    });

    it('compares month to date against the same elapsed window of the previous month', function (): void {
        createDashboardOrder([
            'price_amount' => 10000,
            'created_at' => Carbon::parse('2026-07-10'),
        ]);

        createDashboardOrder([
            'price_amount' => 5000,
            'created_at' => Carbon::parse('2026-06-10'),
        ]);

        createDashboardOrder([
            'price_amount' => 99999,
            'created_at' => Carbon::parse('2026-06-20'),
        ]);

        $cards = Livewire::test(StatCards::class)->instance()->cards;

        expect($cards[0]['change'])->toBe(100.0)
            ->and($cards[0]['trend'])->toBe('up');
    });

    it('counts orders of any payment status except cancelled and archived ones', function (): void {
        createDashboardOrder(['price_amount' => 1000, 'status' => OrderStatus::New, 'payment_status' => PaymentStatus::Pending]);
        createDashboardOrder(['price_amount' => 1000, 'status' => OrderStatus::Processing]);
        createDashboardOrder(['price_amount' => 1000, 'status' => OrderStatus::Cancelled]);
        createDashboardOrder(['price_amount' => 1000, 'status' => OrderStatus::Archived]);

        $cards = Livewire::test(StatCards::class)->instance()->cards;

        expect($cards[2]['value'])->toBe(2);
    });
})->group('dashboard', 'stat-cards');
