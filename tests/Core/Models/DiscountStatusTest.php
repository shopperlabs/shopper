<?php

declare(strict_types=1);

use Shopper\Core\Enum\DiscountStatus;
use Shopper\Core\Models\Discount;

uses(Tests\Core\TestCase::class);

describe('Discount status accessor', function (): void {
    it('returns Disabled when is_active is false', function (): void {
        $discount = Discount::factory()->create([
            'is_active' => false,
            'start_at' => now()->subDay(),
            'end_at' => now()->addDay(),
        ]);

        expect($discount->status)->toBe(DiscountStatus::Disabled);
    });

    it('returns Expired when end_at is in the past', function (): void {
        $discount = Discount::factory()->create([
            'is_active' => true,
            'start_at' => now()->subDays(10),
            'end_at' => now()->subDay(),
        ]);

        expect($discount->status)->toBe(DiscountStatus::Expired);
    });

    it('returns LimitReached when total_use reached usage_limit', function (): void {
        $discount = Discount::factory()->create([
            'is_active' => true,
            'start_at' => now()->subDay(),
            'end_at' => now()->addDay(),
            'usage_limit' => 10,
            'total_use' => 10,
        ]);

        expect($discount->status)->toBe(DiscountStatus::LimitReached);
    });

    it('returns Scheduled when start_at is in the future', function (): void {
        $discount = Discount::factory()->create([
            'is_active' => true,
            'start_at' => now()->addDay(),
            'end_at' => now()->addDays(10),
        ]);

        expect($discount->status)->toBe(DiscountStatus::Scheduled);
    });

    it('returns Active when within the active window without limits hit', function (): void {
        $discount = Discount::factory()->create([
            'is_active' => true,
            'start_at' => now()->subDay(),
            'end_at' => now()->addDay(),
            'usage_limit' => 100,
            'total_use' => 5,
        ]);

        expect($discount->status)->toBe(DiscountStatus::Active);
    });
})->group('models', 'discounts');
