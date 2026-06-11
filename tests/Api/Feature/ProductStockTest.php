<?php

declare(strict_types=1);

use Illuminate\Support\Facades\DB;
use Shopper\Core\Models\Category;
use Shopper\Core\Models\Currency;
use Shopper\Core\Models\Inventory;
use Shopper\Core\Models\Price;
use Shopper\Core\Models\Product;
use Shopper\Core\Models\ProductVariant;

uses(Tests\Api\TestCase::class);

beforeEach(function (): void {
    $this->inventory = Inventory::factory()->create([
        'is_default' => true,
        'priority' => 0,
    ]);
});

function stockedProduct(string $type, array $attributes = []): Product
{
    $product = Product::factory()->{$type}()->create($attributes);

    Price::factory()->create([
        'priceable_id' => $product->id,
        'priceable_type' => $product->getMorphClass(),
        'currency_id' => Currency::query()->value('id'),
        'amount' => 1999,
    ]);

    return $product;
}

it('exposes the stock of a standard product', function (): void {
    $product = stockedProduct('standard', ['name' => 'OwnStock', 'allow_backorder' => false]);
    $product->mutateStock($this->inventory->id, 7, event: 'Initial');

    $attributes = $this->getJson('/store/products/'.$product->slug)
        ->assertOk()
        ->assertJsonPath('data.attributes.stock', 7)
        ->assertJsonPath('data.attributes.in_stock', true)
        ->json('data.attributes');

    expect($attributes)->not->toHaveKey('variants_stock');
});

it('marks a standard product without stock as out of stock', function (): void {
    $product = stockedProduct('standard', ['name' => 'Depleted', 'allow_backorder' => false]);

    $this->getJson('/store/products/'.$product->slug)
        ->assertOk()
        ->assertJsonPath('data.attributes.stock', 0)
        ->assertJsonPath('data.attributes.in_stock', false);
});

it('exposes the stock of each included variant', function (): void {
    $product = stockedProduct('variant', ['name' => 'VariantStock']);
    $stocked = ProductVariant::factory()->create(['product_id' => $product->id, 'allow_backorder' => false]);
    $stocked->mutateStock($this->inventory->id, 3, event: 'Initial');
    $depleted = ProductVariant::factory()->create(['product_id' => $product->id, 'allow_backorder' => false]);

    $variants = collect($this->getJson('/store/products/'.$product->slug.'?include=variants')->assertOk()->json('included'))
        ->where('type', 'variants')
        ->keyBy('id');

    expect($variants->get($stocked->public_id)['attributes'])->toMatchArray(['stock' => 3, 'in_stock' => true])
        ->and($variants->get($depleted->public_id)['attributes'])->toMatchArray(['stock' => 0, 'in_stock' => false]);
});

it('keeps a zero-stock variant purchasable through backorders', function (): void {
    $product = stockedProduct('variant', ['name' => 'Backorderable']);
    ProductVariant::factory()->create(['product_id' => $product->id, 'allow_backorder' => true]);

    $included = collect($this->getJson('/store/products/'.$product->slug.'?include=variants')->assertOk()->json('included'))
        ->firstWhere('type', 'variants');

    expect($included['attributes']['stock'])->toBe(0)
        ->and($included['attributes']['in_stock'])->toBeTrue();
});

it('exposes the variant product availability through its variants on the listing', function (): void {
    $product = stockedProduct('variant', ['name' => 'StockedByVariants']);
    $variant = ProductVariant::factory()->create(['product_id' => $product->id, 'allow_backorder' => false]);
    $variant->mutateStock($this->inventory->id, 5, event: 'Initial');

    $attributes = collect($this->getJson('/store/products?filter[name]=StockedByVariants')->assertOk()->json('data'))
        ->first()['attributes'];

    expect($attributes)->not->toHaveKeys(['stock', 'variants_stock'])
        ->and($attributes['in_stock'])->toBeTrue();
});

it('exposes stock on products included in a category', function (): void {
    $category = Category::factory()->create(['name' => 'Stocked Gear', 'is_enabled' => true]);
    $product = stockedProduct('standard', ['name' => 'CategorizedStock', 'allow_backorder' => false]);
    $product->mutateStock($this->inventory->id, 4, event: 'Initial');
    $product->categories()->attach($category);

    $included = collect(
        $this->getJson('/store/categories/'.$category->slug.'?include=products')->assertOk()->json('included')
    )->firstWhere('type', 'products');

    expect($included['attributes']['stock'])->toBe(4)
        ->and($included['attributes']['in_stock'])->toBeTrue();
});

it('batch-loads stock without lazy queries and with a constant query count', function (): void {
    Product::preventLazyStockLoading();
    ProductVariant::preventLazyStockLoading();

    try {
        foreach (range(1, 2) as $i) {
            stockedProduct('standard', ['name' => 'BulkStock standard '.$i, 'allow_backorder' => false])
                ->mutateStock($this->inventory->id, $i, event: 'Initial');

            $product = stockedProduct('variant', ['name' => 'BulkStock variant '.$i]);
            $variant = ProductVariant::factory()->create(['product_id' => $product->id, 'allow_backorder' => false]);
            $variant->mutateStock($this->inventory->id, $i, event: 'Initial');
        }

        DB::enableQueryLog();
        $response = $this->getJson('/store/products?filter[name]=BulkStock&include=variants')->assertOk();
        $queryCount = count(DB::getQueryLog());
        DB::disableQueryLog();

        expect($response->json('data'))->toHaveCount(4)
            // Bounded and constant regardless of product or variant count: the
            // products', variants', and aggregate stock are three batch queries.
            ->and($queryCount)->toBeLessThanOrEqual(16);
    } finally {
        Product::preventLazyStockLoading(false);
        ProductVariant::preventLazyStockLoading(false);
    }
});
