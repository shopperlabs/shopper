<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Bus;
use Shopper\Core\Enum\DiscountApplyTo;
use Shopper\Core\Models\Discount;
use Shopper\Core\Models\DiscountDetail;
use Shopper\Core\Models\Product;
use Shopper\Jobs\AttachedDiscountToProducts;

uses(Tests\TestCase::class);

it('can be dispatched', function (): void {
    Bus::fake();

    $discount = Discount::factory()->create();
    $products = Product::factory()->count(3)->create();

    AttachedDiscountToProducts::dispatch(
        DiscountApplyTo::Products(),
        $products->pluck('id')->toArray(),
        $discount
    );

    Bus::assertDispatched(AttachedDiscountToProducts::class);
})->group('jobs', 'discount');

it('attaches discount to selected products', function (): void {
    $discount = Discount::factory()->create();
    $products = Product::factory()->count(3)->create();

    $job = new AttachedDiscountToProducts(
        applyTo: DiscountApplyTo::Products(),
        productIds: $products->pluck('id')->toArray(),
        discount: $discount
    );

    $job->handle();

    expect($discount->items()->where('condition', 'apply_to')->count())->toBe(3);

    foreach ($products as $product) {
        $detail = DiscountDetail::query()
            ->where('discount_id', $discount->id)
            ->where('discountable_id', $product->id)
            ->where('condition', 'apply_to')
            ->first();

        expect($detail)->not->toBeNull()
            ->and($detail->discountable_type)->toBe(config('shopper.models.product'));
    }
})->group('jobs', 'discount');

it('removes unselected products from discount', function (): void {
    $discount = Discount::factory()->create();
    $existingProducts = Product::factory()->count(3)->create();

    foreach ($existingProducts as $product) {
        DiscountDetail::factory()->create([
            'discount_id' => $discount->id,
            'discountable_id' => $product->id,
            'discountable_type' => config('shopper.models.product'),
            'condition' => 'apply_to',
        ]);
    }

    $newProducts = Product::factory()->count(2)->create();

    $job = new AttachedDiscountToProducts(
        applyTo: DiscountApplyTo::Products(),
        productIds: $newProducts->pluck('id')->toArray(),
        discount: $discount
    );

    $job->handle();

    expect($discount->items()->where('condition', 'apply_to')->count())->toBe(2);

    foreach ($existingProducts as $product) {
        expect(
            $discount->items()
                ->where('discountable_id', $product->id)
                ->where('condition', 'apply_to')
                ->exists()
        )->toBeFalse();
    }

    foreach ($newProducts as $product) {
        expect(
            $discount->items()
                ->where('discountable_id', $product->id)
                ->where('condition', 'apply_to')
                ->exists()
        )->toBeTrue();
    }
})->group('jobs', 'discount');

it('removes all products when apply_to is not products', function (): void {
    $discount = Discount::factory()->create();

    DiscountDetail::factory()->count(3)->create([
        'discount_id' => $discount->id,
        'condition' => 'apply_to',
    ]);

    $job = new AttachedDiscountToProducts(
        applyTo: DiscountApplyTo::Order(),
        productIds: [],
        discount: $discount
    );

    $job->handle();

    expect($discount->items()->where('condition', 'apply_to')->count())->toBe(0);
})->group('jobs', 'discount');

it('updates existing product discount associations', function (): void {
    $discount = Discount::factory()->create();
    $product = Product::factory()->create();

    DiscountDetail::factory()->create([
        'discount_id' => $discount->id,
        'discountable_id' => $product->id,
        'discountable_type' => config('shopper.models.product'),
        'condition' => 'apply_to',
    ]);

    $initialCount = DiscountDetail::query()
        ->where('discount_id', $discount->id)
        ->where('discountable_id', $product->id)
        ->count();

    $job = new AttachedDiscountToProducts(
        applyTo: DiscountApplyTo::Products(),
        productIds: [$product->id],
        discount: $discount
    );

    $job->handle();

    $finalCount = DiscountDetail::query()
        ->where('discount_id', $discount->id)
        ->where('discountable_id', $product->id)
        ->count();

    expect($finalCount)->toBe($initialCount)
        ->and($finalCount)->toBe(1);
})->group('jobs', 'discount');
