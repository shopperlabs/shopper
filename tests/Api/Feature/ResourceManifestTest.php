<?php

declare(strict_types=1);

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;
use Shopper\Api\Facades\ApiResource;
use Shopper\Api\Http\Resources\BrandResource;
use Shopper\Api\Http\Resources\ProductResource;
use Shopper\Core\Models\Brand;
use Shopper\Core\Models\Category;
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

it('serializes the included relationships of a listing with the replacement resource', function (): void {
    ApiResource::replace(BrandResource::class, CustomBrandResource::class);

    $brand = Brand::factory()->create(['name' => 'BrandList', 'slug' => 'brand-list', 'is_enabled' => true]);
    Product::factory()->publish()->create(['brand_id' => $brand->id]);

    $this->getJson('/store/products?include=brand')
        ->assertOk()
        ->assertJsonPath('included.0.attributes.brand_flag', 'custom');
});

it('eager loads the relations registered for the product resource wherever the replacement resource reads them', function (string $endpoint): void {
    ApiResource::replace(ProductResource::class, CustomProductResource::class);
    ApiResource::eagerLoad(ProductResource::class, ['brand']);

    $brand = Brand::factory()->create(['name' => 'BrandEager', 'is_enabled' => true]);
    $category = Category::factory()->create(['slug' => 'eager-category', 'is_enabled' => true]);

    Product::factory()->publish()->count(3)->create(['brand_id' => $brand->id])
        ->each(fn (Product $product) => $product->categories()->attach($category));

    $brandQueries = 0;
    DB::listen(function (QueryExecuted $query) use (&$brandQueries): void {
        $brandQueries += (int) str_contains($query->sql, shopper_table('brands'));
    });

    $response = $this->getJson($endpoint)->assertOk();

    $products = collect($response->json('included') ?? $response->json('data'))->where('type', 'products');

    expect($products)->toHaveCount(3)
        ->and($products->pluck('attributes.brand_name')->unique()->all())->toBe(['BrandEager'])
        ->and($brandQueries)->toBe(1);
})->with([
    'product listing' => '/store/products',
    'included products' => '/store/categories/eager-category?include=products',
]);

it('accepts the filters a replacement resource adds to the allowlist', function (): void {
    ApiResource::replace(ProductResource::class, CustomProductResource::class);

    $match = Product::factory()->publish()->create(['barcode' => '4006381333931']);
    Product::factory()->publish()->create(['barcode' => '5901234123457']);

    $this->getJson('/store/products?filter[barcode]=4006381333931')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.id', $match->public_id);
});

it('accepts the includes a replacement resource adds to the allowlist', function (string $endpoint): void {
    ApiResource::replace(ProductResource::class, CustomProductResource::class);

    Product::factory()->publish()->create(['slug' => 'supplied']);

    $this->getJson($endpoint)->assertOk();
})->with([
    'product listing' => '/store/products?include=supplier',
    'product detail' => '/store/products/supplied?include=supplier',
]);

it('eager loads the relations registered for the product resource on the products of the cart lines', function (): void {
    ApiResource::replace(ProductResource::class, CustomProductResource::class);
    ApiResource::eagerLoad(ProductResource::class, ['brand']);

    setupCurrencies();
    $currency = Currency::query()->where('code', 'USD')->first();
    $inventory = Inventory::factory()->create();
    $brand = Brand::factory()->create(['name' => 'BrandCart', 'is_enabled' => true]);

    $cartId = $this->postJson('/store/carts')->json('data.id');

    Product::factory()->standard()->publish()->count(3)->create(['brand_id' => $brand->id])
        ->each(function (Product $product) use ($cartId, $currency, $inventory): void {
            $product->prices()->create(['amount' => 2500, 'currency_id' => $currency->id]);
            $product->mutateStock($inventory->id, 50);

            $this->postJson("/store/carts/{$cartId}/lines", [
                'purchasable_type' => 'product',
                'purchasable_id' => $product->public_id,
            ])->assertOk();
        });

    $brandQueries = 0;
    DB::listen(function (QueryExecuted $query) use (&$brandQueries): void {
        $brandQueries += (int) str_contains($query->sql, shopper_table('brands'));
    });

    $products = collect($this->getJson("/store/carts/{$cartId}?include=lines.purchasable")->assertOk()->json('included'))
        ->where('type', 'products');

    expect($products)->toHaveCount(3)
        ->and($products->pluck('attributes.brand_name')->unique()->all())->toBe(['BrandCart'])
        ->and($brandQueries)->toBe(1);
});
