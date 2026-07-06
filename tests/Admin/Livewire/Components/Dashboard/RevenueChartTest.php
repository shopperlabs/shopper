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
use Shopper\Livewire\Components\Dashboard\RevenueChart;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {
    Carbon::setTestNow('2026-07-15 12:00:00');

    $this->actingAs(User::factory()->create(), config('shopper.auth.guard'));
});

afterEach(fn () => Carbon::setTestNow());

describe(RevenueChart::class, function (): void {
    it('nets settled refunds against the month the refund was issued', function (): void {
        $currency = shopper_currency();

        $order = Order::factory()->create([
            'status' => OrderStatus::Completed,
            'payment_status' => PaymentStatus::PartiallyRefunded,
            'shipping_status' => ShippingStatus::Delivered,
            'currency_code' => $currency,
            'price_amount' => 10000,
            'created_at' => Carbon::parse('2026-05-10'),
        ]);

        OrderRefund::factory()->create([
            'order_id' => $order->id,
            'amount' => 3000,
            'currency' => $currency,
            'status' => OrderRefundStatus::Refunded,
            'created_at' => Carbon::parse('2026-07-05'),
        ]);

        $options = Livewire::test(RevenueChart::class)->get('options');

        $data = $options['series'][0]['data'];

        expect($data)->toHaveCount(12)
            ->and($data[9])->toBe(100.0)
            ->and($data[11])->toBe(-30.0);
    });
})->group('dashboard', 'revenue-chart');
