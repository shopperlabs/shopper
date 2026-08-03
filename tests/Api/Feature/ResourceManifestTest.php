<?php

declare(strict_types=1);

use Shopper\Api\Facades\ApiResource;
use Shopper\Api\Http\Resources\BrandResource;
use Shopper\Api\Http\Resources\ProductResource;
use Shopper\Core\Models\Brand;
use Shopper\Core\Models\Currency;
use Shopper\Core\Models\Inventory;
use Shopper\Core\Models\Product;
use Tests\Api\Stubs\CustomBrandResource;
use Tests\Api\Stubs\CustomProductResource;
use Tests\Api\Stubs\ProductStubResource;

uses(Tests\Api\TestCase::class);

it('serializes the listing and the detail with the replacement resource', function (): void {
    ApiResource::replace(ProductResource::class, CustomProductResource::class);

    $product = Product::factory()->publish()->create(['name' => 'Swapped']);

    $this->getJson('/store/products')
        ->assertOk()
        ->assertJsonPath('data.0.attributes.warehouse_code', 'WH-'.$product->id);

    $this->getJson('/store/products/'.$product->slug)
        ->assertOk()
        ->assertJsonPath('data.attributes.warehouse_code', 'WH-'.$product->id);
});

it('serializes nested relationships with the replacement resource', function (): void {
    ApiResource::replace(ProductResource::class, CustomProductResource::class);

    $brand = Brand::factory()->create(['name' => 'BrandSwap', 'slug' => 'brand-swap', 'is_enabled' => true]);
    $product = Product::factory()->publish()->create(['brand_id' => $brand->id]);

    $this->getJson('/store/brands/'.$brand->slug.'?include=products')
        ->assertOk()
        ->assertJsonPath('included.0.type', 'products')
        ->assertJsonPath('included.0.attributes.warehouse_code', 'WH-'.$product->id);
});

it('applies every replacement of a batched call', function (): void {
    ApiResource::replace([
        ProductResource::class => CustomProductResource::class,
        BrandResource::class => CustomBrandResource::class,
    ]);

    $brand = Brand::factory()->create(['name' => 'BrandBatch', 'slug' => 'brand-batch', 'is_enabled' => true]);
    $product = Product::factory()->publish()->create(['brand_id' => $brand->id]);

    $this->getJson('/store/products/'.$product->slug.'?include=brand')
        ->assertOk()
        ->assertJsonPath('data.attributes.warehouse_code', 'WH-'.$product->id)
        ->assertJsonPath('included.0.attributes.brand_flag', 'custom');
});

it('registers nothing when one entry of a batched call is invalid', function (): void {
    expect(fn () => ApiResource::replace([
        ProductResource::class => CustomProductResource::class,
        BrandResource::class => ProductStubResource::class,
    ]))->toThrow(InvalidArgumentException::class);

    $product = Product::factory()->publish()->create();

    expect($this->getJson('/store/products/'.$product->slug)->assertOk()->json('data.attributes'))
        ->not->toHaveKey('warehouse_code');
});

it('serializes the cart purchasable with the replacement resource', function (): void {
    ApiResource::replace(ProductResource::class, CustomProductResource::class);

    setupCurrencies();
    $currency = Currency::query()->where('code', 'USD')->first();
    $inventory = Inventory::factory()->create();

    $product = Product::factory()->standard()->publish()->create();
    $product->prices()->create(['amount' => 2500, 'currency_id' => $currency->id]);
    $product->mutateStock($inventory->id, 50);

    $cartId = $this->postJson('/store/carts')->json('data.id');

    $response = $this->postJson("/store/carts/{$cartId}/lines?include=lines.purchasable", [
        'purchasable_type' => 'product',
        'purchasable_id' => $product->public_id,
    ])->assertOk();

    $included = collect($response->json('included'))->where('type', 'products')->sole();

    expect($included['attributes']['warehouse_code'])->toBe('WH-'.$product->id);
});

it('rejects a replacement that does not extend the stock resource', function (): void {
    expect(fn () => ApiResource::replace(ProductResource::class, ProductStubResource::class))
        ->toThrow(InvalidArgumentException::class);
});
