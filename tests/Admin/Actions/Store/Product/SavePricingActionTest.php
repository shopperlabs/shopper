<?php

declare(strict_types=1);

use Shopper\Actions\Store\Product\SavePricingAction;
use Shopper\Core\Models\Currency;
use Tests\Core\Stubs\Product;
use Tests\Core\Stubs\User;

uses(Tests\TestCase::class);

beforeEach(function (): void {
    config()->set('shopper.models.product', Product::class);

    $this->user = User::factory()->create();
    $this->actingAs($this->user);
});

describe(SavePricingAction::class, function (): void {
    it('saves pricing for model', function (): void {
        $product = Product::factory()->create();
        $currency = Currency::query()->first();

        $action = new SavePricingAction();
        $action(
            pricing: [
                $currency->id => ['amount' => 2500, 'compare_amount' => 3000],
            ],
            model: $product
        );

        $price = $product->prices()->first();

        expect($product->prices()->count())->toBe(1)
            ->and($price->amount)->toBe(2500)
            ->and($price->compare_amount)->toBe(3000);
    });

    it('updates existing pricing', function (): void {
        $product = Product::factory()->create();
        $currency = Currency::query()->first();

        $action = new SavePricingAction();

        $action(
            pricing: [$currency->id => ['amount' => 1000]],
            model: $product
        );

        $action(
            pricing: [$currency->id => ['amount' => 2000]],
            model: $product
        );

        expect($product->prices()->count())->toBe(1)
            ->and($product->prices()->first()->amount)->toBe(2000);
    });
})->group('actions', 'product');
