<?php

declare(strict_types=1);

use Shopper\Core\Enum\DiscountType;
use Shopper\Core\Exceptions\DiscountTermsFrozenException;
use Shopper\Core\Models\Discount;

uses(Tests\Core\TestCase::class);

describe('Discount terms freeze after redemption', function (): void {
    it('freezes the value once the discount has been redeemed', function (): void {
        $discount = Discount::factory()->create([
            'type' => DiscountType::Percentage,
            'value' => 10,
            'total_use' => 3,
        ]);

        expect(fn () => $discount->update(['value' => 20]))
            ->toThrow(DiscountTermsFrozenException::class);
    });

    it('freezes the code once the discount has been redeemed', function (): void {
        $discount = Discount::factory()->create([
            'type' => DiscountType::Percentage,
            'total_use' => 3,
        ]);

        expect(fn () => $discount->update(['code' => 'BRANDNEWCODE']))
            ->toThrow(DiscountTermsFrozenException::class);
    });

    it('allows editing the terms while the discount has no redemptions', function (): void {
        $discount = Discount::factory()->create([
            'type' => DiscountType::Percentage,
            'value' => 10,
            'total_use' => 0,
        ]);

        $discount->update(['value' => 25]);

        expect($discount->fresh()->value)->toBe(25);
    });

    it('still allows editing non-term fields on a redeemed discount', function (): void {
        $discount = Discount::factory()->create([
            'type' => DiscountType::Percentage,
            'is_active' => true,
            'total_use' => 3,
        ]);

        $discount->update(['is_active' => false]);

        expect($discount->fresh()->is_active)->toBeFalse();
    });
})->group('models', 'discounts');
