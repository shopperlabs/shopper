<?php

declare(strict_types=1);

use Shopper\Core\Models\Discount;
use Shopper\Core\Models\DiscountDetail;
use Shopper\Core\Models\Product;

uses(Tests\Core\TestCase::class);

describe(DiscountDetail::class, function (): void {
    it('belongs to discount', function (): void {
        $discount = Discount::factory()->create();
        $product = Product::factory()->create();
        $detail = DiscountDetail::factory()->create([
            'discount_id' => $discount->id,
            'discountable_id' => $product->id,
            'discountable_type' => $product->getMorphClass(),
        ]);

        expect($detail->discount->id)->toBe($discount->id);
    });

    it('has morphable discountable relationship', function (): void {
        $discount = Discount::factory()->create();
        $product = Product::factory()->create();
        $detail = DiscountDetail::factory()->create([
            'discount_id' => $discount->id,
            'discountable_id' => $product->id,
            'discountable_type' => $product->getMorphClass(),
        ]);

        expect($detail->discountable)->toBeInstanceOf(Product::class)
            ->and($detail->discountable->id)->toBe($product->id);
    });
})->group('discount', 'models');
