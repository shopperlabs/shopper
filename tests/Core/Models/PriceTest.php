<?php

declare(strict_types=1);

use Shopper\Core\Models\Currency;
use Shopper\Core\Models\Price;
use Shopper\Core\Models\Product;

uses(Tests\TestCase::class);

describe(Price::class, function (): void {
    it('belongs to currency', function (): void {
        $currency = Currency::query()->first();
        /** @var Product $product */
        $product = Product::factory()->create();

        $price = Price::factory()->create([
            'priceable_id' => $product->id,
            'priceable_type' => $product->getMorphClass(),
            'currency_id' => $currency->id,
            'amount' => 1000,
        ]);

        expect($price->currency->id)->toBe($currency->id);
    });

    it('is morphable to product', function (): void {
        $currency = Currency::query()->first();
        /** @var Product $product */
        $product = Product::factory()->create();

        $price = Price::factory()->create([
            'priceable_id' => $product->id,
            'priceable_type' => $product->getMorphClass(),
            'currency_id' => $currency->id,
            'amount' => 2500,
        ]);

        expect($price->priceable)->toBeInstanceOf(Product::class)
            ->and($price->priceable->id)->toBe($product->id);
    });

    it('has compare amount', function (): void {
        $currency = Currency::query()->first();
        /** @var Product $product */
        $product = Product::factory()->create();

        $price = Price::factory()->create([
            'priceable_id' => $product->id,
            'priceable_type' => $product->getMorphClass(),
            'currency_id' => $currency->id,
            'amount' => 1000,
            'compare_amount' => 1500,
        ]);

        expect($price->compare_amount)->toBe(1500);
    });

    it('converts amount accessor and mutator correctly', function (): void {
        $currency = Currency::query()->first();
        /** @var Product $product */
        $product = Product::factory()->create();

        $price = Price::factory()->create([
            'priceable_id' => $product->id,
            'priceable_type' => $product->getMorphClass(),
            'currency_id' => $currency->id,
            'amount' => 50,
        ]);

        expect($price->fresh()->amount)->toBe(50)
            ->and($price->fresh()->getAttributes()['amount'])->toBe(5000);
    });

    it('converts compare_amount accessor and mutator correctly', function (): void {
        $currency = Currency::query()->first();
        /** @var Product $product */
        $product = Product::factory()->create();

        $price = Price::factory()->create([
            'priceable_id' => $product->id,
            'priceable_type' => $product->getMorphClass(),
            'currency_id' => $currency->id,
            'amount' => 50,
            'compare_amount' => 75,
        ]);

        expect($price->fresh()->compare_amount)->toBe(75)
            ->and($price->fresh()->getAttributes()['compare_amount'])->toBe(7500);
    });

    it('converts cost_amount accessor and mutator correctly', function (): void {
        $currency = Currency::query()->first();
        /** @var Product $product */
        $product = Product::factory()->create();

        $price = Price::factory()->create([
            'priceable_id' => $product->id,
            'priceable_type' => $product->getMorphClass(),
            'currency_id' => $currency->id,
            'amount' => 50,
            'cost_amount' => 30,
        ]);

        expect($price->fresh()->cost_amount)->toBe(30)
            ->and($price->fresh()->getAttributes()['cost_amount'])->toBe(3000);
    });

    it('returns currency code accessor', function (): void {
        $currency = Currency::query()->firstOrCreate(['code' => 'EUR'], [
            'name' => 'Euro',
            'symbol' => '€',
            'format' => '€1,234.56',
        ]);
        /** @var Product $product */
        $product = Product::factory()->create();

        $price = Price::factory()->create([
            'priceable_id' => $product->id,
            'priceable_type' => $product->getMorphClass(),
            'currency_id' => $currency->id,
            'amount' => 50,
        ]);

        expect($price->currency_code)->toBe('EUR');
    });

    it('returns price helper for amount', function (): void {
        $currency = Currency::query()->first();
        /** @var Product $product */
        $product = Product::factory()->create();

        $price = Price::factory()->create([
            'priceable_id' => $product->id,
            'priceable_type' => $product->getMorphClass(),
            'currency_id' => $currency->id,
            'amount' => 50,
        ]);

        expect($price->amountPrice())->toBeInstanceOf(Shopper\Core\Helpers\Price::class);
    });

    it('returns null for amount price when amount is null', function (): void {
        $currency = Currency::query()->first();
        /** @var Product $product */
        $product = Product::factory()->create();

        $price = Price::factory()->create([
            'priceable_id' => $product->id,
            'priceable_type' => $product->getMorphClass(),
            'currency_id' => $currency->id,
        ]);
        $price->update(['amount' => null]);

        expect($price->fresh()->amountPrice())->toBeNull();
    });

    it('returns price helper for compare amount', function (): void {
        $currency = Currency::query()->first();
        /** @var Product $product */
        $product = Product::factory()->create();

        $price = Price::factory()->create([
            'priceable_id' => $product->id,
            'priceable_type' => $product->getMorphClass(),
            'currency_id' => $currency->id,
            'amount' => 50,
            'compare_amount' => 75,
        ]);

        expect($price->compareAmountPrice())->toBeInstanceOf(Shopper\Core\Helpers\Price::class);
    });

    it('returns null for compare amount price when compare amount is null', function (): void {
        $currency = Currency::query()->first();
        /** @var Product $product */
        $product = Product::factory()->create();

        $price = Price::factory()->create([
            'priceable_id' => $product->id,
            'priceable_type' => $product->getMorphClass(),
            'currency_id' => $currency->id,
            'amount' => 50,
            'compare_amount' => null,
        ]);

        expect($price->compareAmountPrice())->toBeNull();
    });

    it('returns price helper for cost amount', function (): void {
        $currency = Currency::query()->first();
        /** @var Product $product */
        $product = Product::factory()->create();

        $price = Price::factory()->create([
            'priceable_id' => $product->id,
            'priceable_type' => $product->getMorphClass(),
            'currency_id' => $currency->id,
            'amount' => 50,
            'compare_amount' => null,
            'cost_amount' => 30,
        ]);

        expect($price->costAmountPrice())->toBeInstanceOf(Shopper\Core\Helpers\Price::class);
    });

    it('returns null for cost amount price when cost amount is null', function (): void {
        $currency = Currency::query()->first();
        /** @var Product $product */
        $product = Product::factory()->create();

        $price = Price::factory()->create([
            'priceable_id' => $product->id,
            'priceable_type' => $product->getMorphClass(),
            'currency_id' => $currency->id,
            'amount' => 50,
            'compare_amount' => null,
            'cost_amount' => null,
        ]);

        expect($price->costAmountPrice())->toBeNull();
    });
})->group('price', 'models');
