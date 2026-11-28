<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Enum\OrderStatus;
use Shopper\Core\Enum\PaymentStatus;
use Shopper\Core\Models\Discount;
use Shopper\Core\Models\Order;
use Shopper\Core\Models\OrderPromotion;
use Shopper\Livewire\Components\Discounts\StatsPanel;

uses(Tests\Admin\TestCase::class);

describe(StatsPanel::class, function (): void {
    it('keeps a partially refunded order in the orders that used the discount', function (): void {
        $discount = Discount::factory()->create();

        foreach ([PaymentStatus::Paid, PaymentStatus::PartiallyRefunded, PaymentStatus::Pending] as $paymentStatus) {
            $order = Order::factory()->create([
                'status' => OrderStatus::Processing,
                'payment_status' => $paymentStatus,
                'price_amount' => 100_00,
            ]);

            OrderPromotion::query()->create([
                'order_id' => $order->id,
                'discount_id' => $discount->id,
                'code' => $discount->code,
                'type' => $discount->type->value,
                'value_at_apply' => $discount->value,
                'amount' => 10_00,
                'currency_code' => $order->currency_code,
            ]);
        }

        $stats = Livewire::test(StatsPanel::class, ['discount' => $discount])->instance()->stats;

        expect($stats['orders_count'])->toBe(2)
            ->and($stats['discount_cost'])->toBe(20_00);
    });
})->group('livewire', 'discounts');
