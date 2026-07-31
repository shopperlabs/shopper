<?php

declare(strict_types=1);

use Shopper\Core\Enum\ProductType;
use Shopper\Core\Models\Brand;
use Shopper\Core\Models\Category;
use Shopper\Core\Models\Channel;
use Shopper\Core\Models\Collection;
use Shopper\Core\Models\Product;

uses(Tests\Api\TestCase::class);

function channelProduct(string $name, ?Channel $channel = null): Product
{
    $product = Product::factory()->publish()->create(['name' => $name, 'type' => ProductType::Standard]);

    if ($channel !== null) {
        $product->channels()->attach($channel->id);
    }

    return $product;
}

function hiddenProduct(string $name): Product
{
    return Product::factory()->create(['name' => $name, 'type' => ProductType::Standard, 'is_visible' => false]);
}

/**
 * @return array<int, string>
 */
function includedProductNames(object $test, string $path, array $headers = []): array
{
    return collect($test->getJson($path, $headers)->assertOk()->json('included'))
        ->where('type', 'products')
        ->pluck('attributes.name')
        ->all();
}

function webstoreChannel(): Channel
{
    return Channel::factory()->create(['slug' => 'webstore', 'is_enabled' => true, 'is_default' => false]);
}

it('restricts the products listing to the channel resolved from the header', function (): void {
    $channel = webstoreChannel();

    channelProduct('On Webstore', $channel);
    channelProduct('Unattached');

    $response = $this->getJson('/store/products', ['X-Shopper-Channel' => 'webstore'])->assertOk();

    $names = collect($response->json('data'))->pluck('attributes.name');

    expect($names->all())->toBe(['On Webstore'])
        ->and($response->headers->all('Vary'))->toHaveCount(1)
        ->and($response->headers->get('Vary'))->toContain('X-Shopper-Channel')
        ->toContain('X-Shopper-Zone');
});

it('keeps the catalog unfiltered without a channel header or when the slug is unknown', function (): void {
    channelProduct('On Webstore', webstoreChannel());
    channelProduct('Unattached');

    $this->getJson('/store/products')->assertOk()->assertJsonCount(2, 'data');
    $this->getJson('/store/products', ['X-Shopper-Channel' => 'unknown'])->assertOk()->assertJsonCount(2, 'data');
});

it('returns a 404 for a product not attached to the resolved channel', function (): void {
    $channel = webstoreChannel();
    $product = channelProduct('Unattached');

    $this->getJson('/store/products/'.$product->slug, ['X-Shopper-Channel' => 'webstore'])->assertNotFound();
    $this->getJson('/store/products/'.$product->slug)->assertOk();

    $attached = channelProduct('On Webstore', $channel);

    $this->getJson('/store/products/'.$attached->slug, ['X-Shopper-Channel' => 'webstore'])->assertOk();
});

it('scopes the products included through a category or a brand to the same channel', function (): void {
    $channel = webstoreChannel();
    $category = Category::factory()->create(['slug' => 'lighting', 'is_enabled' => true]);
    $brand = Brand::factory()->create(['slug' => 'aurora', 'is_enabled' => true]);

    $onChannel = channelProduct('On Webstore', $channel);
    $offChannel = channelProduct('Unattached');
    $hidden = hiddenProduct('Hidden');

    foreach ([$onChannel, $offChannel, $hidden] as $product) {
        $product->categories()->attach($category->id);
        $product->update(['brand_id' => $brand->id]);
    }

    $headers = ['X-Shopper-Channel' => 'webstore'];

    expect(includedProductNames($this, '/store/categories/lighting?include=products', $headers))->toBe(['On Webstore'])
        ->and(includedProductNames($this, '/store/brands/aurora?include=products', $headers))->toBe(['On Webstore'])
        ->and(includedProductNames($this, '/store/categories?include=products', $headers))->toBe(['On Webstore'])
        ->and(includedProductNames($this, '/store/brands?include=products', $headers))->toBe(['On Webstore']);
});

it('never exposes an unpublished product through an include', function (): void {
    $brand = Brand::factory()->create(['slug' => 'aurora', 'is_enabled' => true]);

    Product::factory()->publish()->create(['name' => 'Published', 'type' => ProductType::Standard, 'brand_id' => $brand->id]);
    Product::factory()->create(['name' => 'Hidden', 'type' => ProductType::Standard, 'is_visible' => false, 'brand_id' => $brand->id]);

    expect(includedProductNames($this, '/store/brands/aurora?include=products'))->toBe(['Published'])
        ->and(includedProductNames($this, '/store/brands?include=products'))->toBe(['Published']);
});

it('scopes the products included through a collection to the same channel', function (): void {
    $channel = webstoreChannel();
    $collection = Collection::factory()->create(['slug' => 'summer', 'published_at' => now()->subDay()]);

    $onChannel = channelProduct('On Webstore', $channel);
    $offChannel = channelProduct('Unattached');
    $hidden = hiddenProduct('Hidden');

    foreach ([$onChannel, $offChannel, $hidden] as $product) {
        $product->collections()->attach($collection->id);
    }

    expect(includedProductNames($this, '/store/collections/summer?include=products', ['X-Shopper-Channel' => 'webstore']))
        ->toBe(['On Webstore'])
        ->and(includedProductNames($this, '/store/collections/summer?include=products'))
        ->toBe(['On Webstore', 'Unattached']);
});

it('scopes the related products of a product to the same channel', function (): void {
    $channel = webstoreChannel();

    $product = channelProduct('Main', $channel);
    $onChannel = channelProduct('Related On Webstore', $channel);
    $offChannel = channelProduct('Related Unattached');
    $hidden = hiddenProduct('Related Hidden');

    $product->relatedProducts()->attach([$onChannel->id, $offChannel->id, $hidden->id]);

    $headers = ['X-Shopper-Channel' => 'webstore'];

    expect(includedProductNames($this, '/store/products/'.$product->slug.'?include=relatedProducts', $headers))
        ->toBe(['Related On Webstore'])
        ->and(includedProductNames($this, '/store/products?include=relatedProducts', $headers))
        ->toBe(['Related On Webstore']);
});

it('never resolves a disabled channel', function (): void {
    $channel = Channel::factory()->create(['slug' => 'webstore', 'is_enabled' => false, 'is_default' => false]);

    channelProduct('On Webstore', $channel);
    channelProduct('Unattached');

    $this->getJson('/store/products', ['X-Shopper-Channel' => 'webstore'])->assertOk()->assertJsonCount(2, 'data');
});
