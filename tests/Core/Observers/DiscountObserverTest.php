<?php

declare(strict_types=1);

use Shopper\Core\Enum\DiscountType;
use Shopper\Core\Exceptions\InvalidDiscountValueException;
use Shopper\Core\Models\Discount;

uses(Tests\Core\TestCase::class);

describe('DiscountObserver', function (): void {
    it('persists a valid discount', function (): void {
        $discount = Discount::factory()->create([
            'type' => DiscountType::FixedAmount,
            'value' => 5000,
        ]);

        expect($discount->exists)->toBeTrue()
            ->and($discount->value)->toBe(5000);
    });

    it('blocks creating a discount with a negative value', function (): void {
        expect(fn () => Discount::factory()->create([
            'type' => DiscountType::FixedAmount,
            'value' => -5000,
        ]))->toThrow(InvalidDiscountValueException::class);

        expect(Discount::query()->count())->toBe(0);
    });

    it('blocks creating a discount with a zero value', function (): void {
        expect(fn () => Discount::factory()->create([
            'type' => DiscountType::Percentage,
            'value' => 0,
        ]))->toThrow(InvalidDiscountValueException::class);

        expect(Discount::query()->count())->toBe(0);
    });

    it('blocks creating a percentage discount above 100', function (): void {
        expect(fn () => Discount::factory()->create([
            'type' => DiscountType::Percentage,
            'value' => 150,
        ]))->toThrow(InvalidDiscountValueException::class);

        expect(Discount::query()->count())->toBe(0);
    });

    it('allows a percentage discount of exactly 100', function (): void {
        $discount = Discount::factory()->create([
            'type' => DiscountType::Percentage,
            'value' => 100,
        ]);

        expect($discount->value)->toBe(100);
    });

    it('blocks updating a valid discount to a negative value', function (): void {
        $discount = Discount::factory()->create([
            'type' => DiscountType::FixedAmount,
            'value' => 5000,
        ]);

        expect(fn () => $discount->update(['value' => -1]))
            ->toThrow(InvalidDiscountValueException::class);

        expect($discount->fresh()->value)->toBe(5000);
    });
})->group('core', 'observers', 'discounts');
