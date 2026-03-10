<?php

declare(strict_types=1);

use Livewire\Livewire;
use Shopper\Core\Models\Currency;
use Shopper\Livewire\SlideOvers\ManagePricing;
use Tests\Core\Stubs\Product;
use Tests\Core\Stubs\ProductVariant;
use Tests\Core\Stubs\User;

uses(Tests\Admin\TestCase::class);

beforeEach(function (): void {

    setupCurrencies(['USD', 'EUR']);

    $this->user = User::factory()->create();
    $this->user->givePermissionTo('edit_products');
    $this->actingAs($this->user);
});

describe(ManagePricing::class, function (): void {
    it('can render manage pricing for product', function (): void {
        $product = Product::factory()->create();

        Livewire::test(ManagePricing::class, [
            'modelId' => $product->id,
            'modelType' => Product::class,
        ])
            ->assertOk();
    });

    it('can render manage pricing for variant', function (): void {
        $variant = ProductVariant::factory()->create();

        Livewire::test(ManagePricing::class, [
            'modelId' => $variant->id,
            'modelType' => ProductVariant::class,
        ])
            ->assertOk();
    });

    it('loads model with prices on mount', function (): void {
        $product = Product::factory()->create();

        $component = Livewire::test(ManagePricing::class, [
            'modelId' => $product->id,
            'modelType' => Product::class,
        ]);

        expect($component->get('model'))->not->toBeNull()
            ->and($component->get('model')->id)->toBe($product->id);
    });

    it('loads currencies from settings', function (): void {
        $product = Product::factory()->create();

        $component = Livewire::test(ManagePricing::class, [
            'modelId' => $product->id,
            'modelType' => Product::class,
        ]);

        $currencies = $component->get('currencies');

        expect($currencies)->toHaveCount(2)
            ->and($currencies->pluck('code')->toArray())->toContain('USD', 'EUR');
    });

    it('loads single currency when currencyId is provided', function (): void {
        $product = Product::factory()->create();
        $usdCurrency = Currency::query()->where('code', 'USD')->first();

        $component = Livewire::test(ManagePricing::class, [
            'modelId' => $product->id,
            'modelType' => Product::class,
            'currencyId' => $usdCurrency->id,
        ]);

        $currencies = $component->get('currencies');

        expect($currencies)->toHaveCount(1)
            ->and($currencies->first()->code)->toBe('USD');
    });

    it('can save pricing for product', function (): void {
        $product = Product::factory()->create();
        $usdCurrency = Currency::query()->where('code', 'USD')->first();

        Livewire::test(ManagePricing::class, [
            'modelId' => $product->id,
            'modelType' => Product::class,
        ])
            ->fillForm([
                $usdCurrency->id => [
                    'amount' => 100,
                    'compare_amount' => 150,
                ],
            ])
            ->call('save')
            ->assertDispatched('product.pricing.manage');

        $product->refresh();

        expect($product->prices)->toHaveCount(1)
            ->and($product->prices->first()->amount)->toBe(100);
    });

    it('dispatches event after saving pricing', function (): void {
        $product = Product::factory()->create();

        Livewire::test(ManagePricing::class, [
            'modelId' => $product->id,
            'modelType' => Product::class,
        ])
            ->call('save')
            ->assertDispatched('product.pricing.manage');
    });
})->group('livewire', 'slideovers', 'products');
