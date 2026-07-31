<?php

declare(strict_types=1);

use Shopper\Core\Enum\ProductType;
use Shopper\Core\Models\Currency;
use Shopper\Core\Models\Price;
use Shopper\Core\Models\Product;
use Shopper\Core\Models\ProductVariant;
use Shopper\Core\Models\Zone;

uses(Tests\Api\TestCase::class);

function pricingCurrency(string $code): Currency
{
    return Currency::withoutGlobalScopes()->where('code', $code)->firstOrFail();
}

function pricedProduct(string $name, int $amount, string $currencyCode = 'USD'): Product
{
    $product = Product::factory()->publish()->create(['name' => $name, 'type' => ProductType::Standard]);

    Price::factory()->create([
        'priceable_id' => $product->id,
        'priceable_type' => $product->getMorphClass(),
        'currency_id' => pricingCurrency($currencyCode)->id,
        'amount' => $amount,
    ]);

    return $product;
}

function variantPricedProduct(string $name, array $amounts, string $currencyCode = 'USD'): Product
{
    $product = Product::factory()->publish()->create(['name' => $name, 'type' => ProductType::Variant]);

    foreach ($amounts as $amount) {
        $variant = ProductVariant::factory()->create(['product_id' => $product->id]);

        Price::factory()->create([
            'priceable_id' => $variant->id,
            'priceable_type' => $variant->getMorphClass(),
            'currency_id' => pricingCurrency($currencyCode)->id,
            'amount' => $amount,
        ]);
    }

    return $product;
}

it('sorts products by price in the resolved currency, branched by product type', function (): void {
    pricedProduct('Expensive', 5000);
    pricedProduct('Cheap', 2000);
    variantPricedProduct('Middle', [3000, 9000]);

    $names = collect($this->getJson('/store/products?sort=price')->assertOk()->json('data'))
        ->pluck('attributes.name');

    expect($names->toArray())->toBe(['Cheap', 'Middle', 'Expensive']);

    $names = collect($this->getJson('/store/products?sort=-price')->assertOk()->json('data'))
        ->pluck('attributes.name');

    expect($names->toArray())->toBe(['Expensive', 'Middle', 'Cheap']);
});

it('excludes products without a price in the resolved currency from the price sort', function (): void {
    pricedProduct('Priced', 5000);
    Product::factory()->publish()->create(['name' => 'Unpriced', 'type' => ProductType::Standard]);

    $response = $this->getJson('/store/products?sort=price')->assertOk();

    expect(collect($response->json('data'))->pluck('attributes.name')->toArray())->toBe(['Priced']);
});

it('never surfaces stale product-level prices on a variant product', function (): void {
    $product = variantPricedProduct('Converted', [2900, 3900]);

    Price::factory()->create([
        'priceable_id' => $product->id,
        'priceable_type' => $product->getMorphClass(),
        'currency_id' => pricingCurrency('USD')->id,
        'amount' => 1900,
    ]);

    $this->getJson('/store/products?sort=price')
        ->assertOk()
        ->assertJsonPath('data.0.attributes.price_range.min', 2900)
        ->assertJsonPath('data.0.attributes.price_range.max', 3900)
        ->assertJsonPath('data.0.attributes.prices', []);
});

it('filters products by price bounds in minor units', function (): void {
    pricedProduct('Low', 1000);
    pricedProduct('Mid', 4000);
    pricedProduct('High', 9000);

    $names = collect(
        $this->getJson('/store/products?filter[price_min]=2500&filter[price_max]=6000&sort=price')
            ->assertOk()
            ->json('data')
    )->pluck('attributes.name');

    expect($names->toArray())->toBe(['Mid']);
});

it('exposes the price range and the resolved currency on listings and detail', function (): void {
    $product = pricedProduct('Ranged', 2999);

    $this->getJson('/store/products')
        ->assertOk()
        ->assertJsonPath('data.0.attributes.price_range.currency_code', 'USD')
        ->assertJsonPath('data.0.attributes.price_range.min', 2999)
        ->assertJsonPath('data.0.attributes.price_range.max', 2999)
        ->assertJsonPath('meta.currency', 'USD');

    $this->getJson('/store/products/'.$product->slug)
        ->assertOk()
        ->assertJsonPath('data.attributes.price_range.min', 2999)
        ->assertJsonPath('meta.currency', 'USD');
});

it('returns a null price range for a product without a price in the resolved currency', function (): void {
    Product::factory()->publish()->create(['name' => 'Bare', 'type' => ProductType::Standard]);

    $this->getJson('/store/products')
        ->assertOk()
        ->assertJsonPath('data.0.attributes.price_range', null);
});

it('resolves the currency from an explicit filter before the zone header', function (): void {
    $product = pricedProduct('Dual', 5000);

    Price::factory()->create([
        'priceable_id' => $product->id,
        'priceable_type' => $product->getMorphClass(),
        'currency_id' => pricingCurrency('EUR')->id,
        'amount' => 4600,
    ]);

    Zone::factory()->create(['code' => 'us-zone', 'is_enabled' => true, 'currency_id' => pricingCurrency('USD')->id]);

    $this->getJson('/store/products?filter[currency]=eur', ['X-Shopper-Zone' => 'us-zone'])
        ->assertOk()
        ->assertJsonPath('meta.currency', 'EUR')
        ->assertJsonPath('data.0.attributes.price_range.min', 4600);
});

it('resolves the currency from the zone header when no filter is given', function (): void {
    $product = pricedProduct('Zoned', 5000);

    Price::factory()->create([
        'priceable_id' => $product->id,
        'priceable_type' => $product->getMorphClass(),
        'currency_id' => pricingCurrency('EUR')->id,
        'amount' => 4600,
    ]);

    Zone::factory()->create(['code' => 'eu-zone', 'is_enabled' => true, 'currency_id' => pricingCurrency('EUR')->id]);

    $response = $this->getJson('/store/products', ['X-Shopper-Zone' => 'eu-zone'])
        ->assertOk()
        ->assertJsonPath('meta.currency', 'EUR')
        ->assertJsonPath('data.0.attributes.price_range.min', 4600);

    expect((string) $response->headers->get('Vary'))->toContain('X-Shopper-Zone');
});

it('rejects an unknown or disabled currency code', function (): void {
    pricedProduct('Any', 1000);

    Currency::factory()->create(['code' => 'ZZY', 'name' => 'Disabled Coin', 'is_enabled' => false]);

    $this->getJson('/store/products?filter[currency]=NOPE')->assertStatus(422);
    $this->getJson('/store/products?filter[currency]=ZZY')->assertStatus(422);
});

it('filters featured products through the allowlist', function (): void {
    Product::factory()->publish()->create(['name' => 'Starred', 'featured' => true, 'type' => ProductType::Standard]);
    Product::factory()->publish()->create(['name' => 'Plain', 'featured' => false, 'type' => ProductType::Standard]);

    $names = collect(
        $this->getJson('/store/products?filter[featured]=true')->assertOk()->json('data')
    )->pluck('attributes.name');

    expect($names->toArray())->toBe(['Starred']);
});

it('excludes a variant product with no variants from the price sort and shows a null price range', function (): void {
    Product::factory()->publish()->create(['name' => 'EmptyVariant', 'type' => ProductType::Variant]);
    pricedProduct('Priced', 5000);

    $sorted = collect($this->getJson('/store/products?sort=price')->assertOk()->json('data'))
        ->pluck('attributes.name');

    expect($sorted->toArray())->toBe(['Priced']);

    $listing = collect($this->getJson('/store/products')->assertOk()->json('data'));

    expect($listing->firstWhere('attributes.name', 'EmptyVariant')['attributes']['price_range'])->toBeNull();
});

it('filters by a price bound alone without a price sort', function (): void {
    pricedProduct('Low', 1000);
    pricedProduct('Mid', 4000);
    Product::factory()->publish()->create(['name' => 'Unpriced', 'type' => ProductType::Standard]);

    $names = collect($this->getJson('/store/products?filter[price_min]=2500')->assertOk()->json('data'))
        ->pluck('attributes.name');

    expect($names->toArray())->toBe(['Mid']);
});

it('exposes the price range on sideloaded related products', function (): void {
    $product = pricedProduct('Main', 5000);
    $related = pricedProduct('Related', 3000);
    $product->relatedProducts()->attach($related->id);

    $included = collect(
        $this->getJson('/store/products/'.$product->slug.'?include=relatedProducts')->assertOk()->json('included')
    )->firstWhere('type', 'products');

    expect($included['attributes']['price_range']['min'])->toBe(3000);
});

it('combines a text search filter with a price sort', function (): void {
    pricedProduct('Cheap Shirt', 2000);
    pricedProduct('Expensive Shirt', 8000);
    pricedProduct('Cheap Hat', 1500);

    $names = collect($this->getJson('/store/products?filter[q]=shirt&sort=price')->assertOk()->json('data'))
        ->pluck('attributes.name');

    expect($names->toArray())->toBe(['Cheap Shirt', 'Expensive Shirt']);
});

it('falls back to the default currency when the zone header references a disabled zone', function (): void {
    pricedProduct('DisabledZoneFallback', 5000);
    Zone::factory()->create(['code' => 'disabled-zone', 'is_enabled' => false, 'currency_id' => pricingCurrency('EUR')->id]);

    $this->getJson('/store/products', ['X-Shopper-Zone' => 'disabled-zone'])
        ->assertOk()
        ->assertJsonPath('meta.currency', 'USD')
        ->assertJsonPath('data.0.attributes.price_range.min', 5000);
});

it('includes external products in the price sort and price range', function (): void {
    $external = Product::factory()->publish()->create(['name' => 'External', 'type' => ProductType::External]);

    Price::factory()->create([
        'priceable_id' => $external->id,
        'priceable_type' => $external->getMorphClass(),
        'currency_id' => pricingCurrency('USD')->id,
        'amount' => 3300,
    ]);

    pricedProduct('Cheaper', 1000);

    $names = collect($this->getJson('/store/products?sort=price')->assertOk()->json('data'))
        ->pluck('attributes.name');

    expect($names->toArray())->toBe(['Cheaper', 'External']);
});

it('keeps products with a null type in the price sort as own-price products', function (): void {
    $product = Product::factory()->publish()->create(['name' => 'Legacy', 'type' => null]);

    Price::factory()->create([
        'priceable_id' => $product->id,
        'priceable_type' => $product->getMorphClass(),
        'currency_id' => pricingCurrency('USD')->id,
        'amount' => 2000,
    ]);

    pricedProduct('Modern', 4000);

    $names = collect($this->getJson('/store/products?sort=price')->assertOk()->json('data'))
        ->pluck('attributes.name');

    expect($names->toArray())->toBe(['Legacy', 'Modern']);
});

it('walks an offset paginated price sort with tied prices without duplicates', function (): void {
    pricedProduct('TieA', 999);
    pricedProduct('TieB', 999);
    pricedProduct('TieC', 999);

    $names = [];

    foreach ([1, 2] as $page) {
        $response = $this->getJson('/store/products?sort=price&page[size]=2&page[number]='.$page)->assertOk();

        foreach ($response->json('data') as $product) {
            $names[] = $product['attributes']['name'];
        }
    }

    expect(array_unique($names))->toHaveCount(3);
});

it('never resolves a disabled default currency and keeps the listing working', function (): void {
    pricedProduct('Orphan', 1000);

    Illuminate\Support\Facades\DB::table(shopper_table('currencies'))->update(['is_enabled' => false]);

    $this->getJson('/store/products')
        ->assertOk()
        ->assertJsonPath('meta.currency', null)
        ->assertJsonPath('data.0.attributes.price_range', null)
        ->assertJsonPath('data.0.attributes.prices', []);

    $this->getJson('/store/products?sort=price')->assertStatus(422);
    $this->getJson('/store/products?filter[price_min]=100')->assertStatus(422);
});

it('ignores an oversized zone header without failing the request', function (): void {
    pricedProduct('Resilient', 1000);

    $this->getJson('/store/products', ['X-Shopper-Zone' => str_repeat('A', 300)])
        ->assertOk()
        ->assertJsonPath('meta.currency', 'USD');
});

it('enforces a single price row per priceable and currency', function (): void {
    $product = pricedProduct('Unique', 1000);

    expect(fn () => Price::factory()->create([
        'priceable_id' => $product->id,
        'priceable_type' => $product->getMorphClass(),
        'currency_id' => pricingCurrency('USD')->id,
        'amount' => 2000,
    ]))->toThrow(Illuminate\Database\QueryException::class);
});

it('walks a price-sorted listing through cursor pagination without overlap', function (): void {
    pricedProduct('CursorPriceA', 1000);
    pricedProduct('CursorPriceB', 2000);
    pricedProduct('CursorPriceC', 3000);
    pricedProduct('CursorPriceD', 4000);
    pricedProduct('CursorPriceE', 5000);

    $names = [];
    $url = '/store/products?sort=price&page[size]=2&page[cursor]=';

    do {
        $response = $this->getJson($url)->assertOk();

        foreach ($response->json('data') as $product) {
            $names[] = $product['attributes']['name'];
        }

        $url = $response->json('links.next');
    } while ($url !== null);

    expect($names)->toBe(['CursorPriceA', 'CursorPriceB', 'CursorPriceC', 'CursorPriceD', 'CursorPriceE']);
});
