<?php

declare(strict_types=1);

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Shopper\Core\Enum\ProductType;
use Shopper\Core\Models\Attribute;
use Shopper\Core\Models\AttributeValue;
use Shopper\Core\Models\Brand;
use Shopper\Core\Models\Category;
use Shopper\Core\Models\Contracts\AttributeProduct as AttributeProductContract;
use Shopper\Core\Models\Currency;
use Shopper\Core\Models\Price;
use Shopper\Core\Models\Product;
use Shopper\Core\Models\ProductVariant;
use Shopper\Core\Models\Review;

uses(Tests\Api\TestCase::class);

function publishedProduct(array $attributes = []): Product
{
    $product = Product::factory()->publish()->create($attributes);

    Price::factory()->create([
        'priceable_id' => $product->id,
        'priceable_type' => $product->getMorphClass(),
        'currency_id' => Currency::query()->value('id'),
        'amount' => 2999,
        'compare_amount' => 3999,
    ]);

    return $product;
}

it('shows a product by slug as a JSON:API document', function (): void {
    $product = publishedProduct(['name' => 'Air Max']);

    $this->getJson('/store/products/'.$product->slug)
        ->assertOk()
        ->assertHeader('Content-Type', 'application/vnd.api+json')
        ->assertJsonPath('data.type', 'products')
        ->assertJsonPath('data.id', $product->public_id)
        ->assertJsonPath('data.attributes.name', 'Air Max')
        ->assertJsonPath('data.attributes.slug', $product->slug)
        ->assertJsonPath('data.attributes.prices.0.amount', 2999)
        ->assertJsonPath('data.attributes.prices.0.compare_amount', 3999)
        ->assertJsonPath('data.attributes.prices.0.currency_code', $product->prices()->first()->currency_code);
});

it('falls back to the first gallery image for the thumbnail payload', function (): void {
    $product = publishedProduct(['name' => 'Camera']);

    $image = Tests\Core\Stubs\Product::query()->findOrFail($product->id)
        ->addMedia(UploadedFile::fake()->image('gallery.png', 10, 10))
        ->toMediaCollection((string) config('shopper.media.storage.collection_name'));

    $this->getJson('/store/products/'.$product->slug)
        ->assertOk()
        ->assertJsonPath('data.attributes.thumbnail.id', $image->uuid)
        ->assertJsonPath('data.attributes.images.0.id', $image->uuid);
});

it('prefers the dedicated thumbnail over the gallery in the payload', function (): void {
    $product = publishedProduct(['name' => 'Lens']);
    $bound = Tests\Core\Stubs\Product::query()->findOrFail($product->id);

    $bound->addMedia(UploadedFile::fake()->image('gallery.png', 10, 10))
        ->toMediaCollection((string) config('shopper.media.storage.collection_name'));
    $thumbnail = $bound->addMedia(UploadedFile::fake()->image('thumb.png', 10, 10))
        ->toMediaCollection((string) config('shopper.media.storage.thumbnail_collection'));

    $this->getJson('/store/products/'.$product->slug)
        ->assertOk()
        ->assertJsonPath('data.attributes.thumbnail.id', $thumbnail->uuid);
});

it('does not fall back to the gallery for the category thumbnail payload', function (): void {
    $category = Tests\Core\Stubs\Category::factory()->create(['is_enabled' => true]);

    $category->addMedia(UploadedFile::fake()->image('gallery.png', 10, 10))
        ->toMediaCollection((string) config('shopper.media.storage.collection_name'));

    $this->getJson('/store/categories/'.$category->slug)
        ->assertOk()
        ->assertJsonPath('data.attributes.thumbnail', null);
});

it('exposes a ULID public id, never the primary key', function (): void {
    $product = publishedProduct(['name' => 'Opaque']);

    $id = $this->getJson('/store/products/'.$product->slug)->json('data.id');

    expect($id)->toBe($product->public_id)
        ->and($id)->not->toBe((string) $product->getKey());
});

it('returns a JSON:API 404 error for an unknown slug', function (): void {
    $this->getJson('/store/products/does-not-exist')
        ->assertNotFound()
        ->assertHeader('Content-Type', 'application/vnd.api+json')
        ->assertJsonPath('errors.0.status', '404')
        ->assertJsonPath('errors.0.title', 'Not Found');
});

it('lists only published products', function (): void {
    $visible = publishedProduct(['name' => 'CatalogVisible']);
    $hidden = Product::factory()->create(['name' => 'CatalogHidden', 'is_visible' => false]);

    $ids = collect($this->getJson('/store/products?filter[name]=Catalog')->assertOk()->json('data'))
        ->pluck('id');

    expect($ids)->toContain($visible->public_id)
        ->and($ids)->not->toContain($hidden->public_id);
});

it('eager-loads variant prices when including variants, avoiding N+1', function (): void {
    $product = publishedProduct(['name' => 'WithVariants', 'type' => ProductType::Variant]);
    $currencyId = Currency::query()->value('id');

    foreach (range(1, 3) as $i) {
        $variant = ProductVariant::factory()->create(['product_id' => $product->id]);
        Price::factory()->create([
            'priceable_id' => $variant->id,
            'priceable_type' => $variant->getMorphClass(),
            'currency_id' => $currencyId,
            'amount' => 1000 + $i,
        ]);
    }

    DB::enableQueryLog();
    $response = $this->getJson('/store/products/'.$product->slug.'?include=variants')->assertOk();
    $queryCount = count(DB::getQueryLog());
    DB::disableQueryLog();

    $variants = collect($response->json('included'))->where('type', 'variants');

    expect($variants)->toHaveCount(3)
        ->and($variants->first()['attributes']['prices'])->not->toBeEmpty()
        // Bounded and constant regardless of variant count: prices.currency and
        // values.attribute are eager-loaded once, never per variant, and stock
        // is batch-loaded in three queries.
        ->and($queryCount)->toBeLessThanOrEqual(16);
});

it('includes the brand relationship on demand', function (): void {
    $brand = Brand::factory()->create();
    $product = publishedProduct(['name' => 'Branded', 'brand_id' => $brand->id]);

    $this->getJson('/store/products/'.$product->slug.'?include=brand')
        ->assertOk()
        ->assertJsonPath('data.relationships.brand.data.type', 'brands')
        ->assertJsonPath('data.relationships.brand.data.id', $brand->public_id)
        ->assertJsonPath('included.0.type', 'brands');
});

it('filters products by category', function (): void {
    $category = Category::factory()->create(['name' => 'Phones', 'slug' => 'phones']);
    $inCategory = publishedProduct(['name' => 'Pixel']);
    $inCategory->categories()->attach($category);
    publishedProduct(['name' => 'Sneaker']);

    $names = collect($this->getJson('/store/products?filter[category]=phones')->assertOk()->json('data'))
        ->pluck('attributes.name');

    expect($names)->toContain('Pixel')->and($names)->not->toContain('Sneaker');
});

it('searches products by term', function (): void {
    publishedProduct(['name' => 'Wireless Headphones']);
    publishedProduct(['name' => 'Running Shoes']);

    $names = collect($this->getJson('/store/products?filter[q]=headphone')->assertOk()->json('data'))
        ->pluck('attributes.name');

    expect($names)->toContain('Wireless Headphones')->and($names)->not->toContain('Running Shoes');
});

it('exposes variant option values for option matching', function (): void {
    $product = publishedProduct(['name' => 'Tee', 'type' => ProductType::Variant]);
    $attribute = Attribute::factory()->create(['name' => 'Color']);
    $value = AttributeValue::factory()->create([
        'attribute_id' => $attribute->id,
        'value' => 'Red',
        'key' => 'red',
    ]);
    ProductVariant::factory()->create(['product_id' => $product->id])->values()->attach($value);

    $variant = collect($this->getJson('/store/products/'.$product->slug.'?include=variants')->assertOk()->json('included'))
        ->firstWhere('type', 'variants');

    expect($variant['attributes']['values'])->toHaveCount(1)
        ->and($variant['attributes']['values'][0])->toMatchArray([
            'value' => 'Red',
            'key' => 'red',
            'attribute' => 'Color',
        ]);
});

it('serializes product options as a deduplicated array scoped to the used values', function (): void {
    $product = publishedProduct(['name' => 'Phone', 'type' => ProductType::Standard]);
    $color = Attribute::factory()->create(['name' => 'Color']);
    $red = AttributeValue::factory()->create(['attribute_id' => $color->id, 'value' => 'Red', 'key' => 'red']);
    $blue = AttributeValue::factory()->create(['attribute_id' => $color->id, 'value' => 'Blue', 'key' => 'blue']);
    // A global value the product does NOT use: must be excluded from its options.
    AttributeValue::factory()->create(['attribute_id' => $color->id, 'value' => 'Green', 'key' => 'green']);
    $product->options()->attach($color->id, ['attribute_value_id' => $red->id]);
    $product->options()->attach($color->id, ['attribute_value_id' => $blue->id]);

    $response = $this->getJson('/store/products/'.$product->slug.'?include=options')->assertOk();
    $optionsData = $response->json('data.relationships.options.data');
    $colorOption = collect($response->json('included'))->firstWhere('type', 'attributes');
    $values = collect($colorOption['attributes']['values'])->pluck('value');

    expect($optionsData)->toBeArray()->toHaveCount(1)
        ->and(array_is_list($optionsData))->toBeTrue()
        ->and($values)->toContain('Red', 'Blue')->not->toContain('Green');
});

it('exposes a per-product swatch image url on option values when one is attached', function (): void {
    Storage::fake('public');

    $product = publishedProduct(['name' => 'Tee', 'type' => ProductType::Standard]);
    $color = Attribute::factory()->create(['name' => 'Color']);
    $red = AttributeValue::factory()->create(['attribute_id' => $color->id, 'value' => 'Red', 'key' => '#ff0000']);
    $blue = AttributeValue::factory()->create(['attribute_id' => $color->id, 'value' => 'Blue', 'key' => '#0000ff']);
    $product->options()->attach($color->id, ['attribute_value_id' => $red->id]);
    $product->options()->attach($color->id, ['attribute_value_id' => $blue->id]);

    $redRow = resolve(AttributeProductContract::class)
        ->newQuery()
        ->where('product_id', $product->id)
        ->where('attribute_value_id', $red->id)
        ->firstOrFail();

    $file = UploadedFile::fake()->image('red.png', 10, 10);
    $redRow->addMedia($file)
        ->usingFileName('red.png')
        ->toMediaCollection('swatch');

    $colorOption = collect($this->getJson('/store/products/'.$product->slug.'?include=options')->assertOk()->json('included'))
        ->firstWhere('type', 'attributes');
    $values = collect($colorOption['attributes']['values'])->keyBy('value');

    expect($values['Red']['swatch_url'])->toBeString()->toContain('red.png')
        ->and($values['Blue']['swatch_url'])->toBeNull();
});

it('exposes the review aggregates only when the rating include is requested', function (): void {
    $product = publishedProduct(['name' => 'Rated']);
    $reviewable = ['reviewrateable_id' => $product->id, 'reviewrateable_type' => $product->getMorphClass()];
    Review::factory()->create([...$reviewable, 'rating' => 4, 'approved' => true]);
    Review::factory()->create([...$reviewable, 'rating' => 5, 'approved' => true]);
    Review::factory()->create([...$reviewable, 'rating' => 1, 'approved' => false]);

    $default = $this->getJson('/store/products/'.$product->slug)->assertOk()->json('data.attributes');
    $rated = $this->getJson('/store/products/'.$product->slug.'?include=rating')->assertOk()->json('data.attributes');

    expect($default)->not->toHaveKeys(['rating', 'reviews_count'])
        ->and($rated['reviews_count'])->toBe(2)
        ->and($rated['rating'])->toBe(4.5);
});

it('returns a null rating and zero count without approved reviews', function (): void {
    $product = publishedProduct(['name' => 'Unrated']);

    $this->getJson('/store/products/'.$product->slug.'?include=rating')
        ->assertOk()
        ->assertJsonPath('data.attributes.rating', null)
        ->assertJsonPath('data.attributes.reviews_count', 0);
});

it('includes the review aggregates on the product listing', function (): void {
    $product = publishedProduct(['name' => 'RatedList']);
    $reviewable = ['reviewrateable_id' => $product->id, 'reviewrateable_type' => $product->getMorphClass()];
    Review::factory()->create([...$reviewable, 'rating' => 3, 'approved' => true]);
    Review::factory()->create([...$reviewable, 'rating' => 4, 'approved' => true]);

    $attributes = collect(
        $this->getJson('/store/products?filter[name]=RatedList&include=rating')->assertOk()->json('data')
    )->first()['attributes'];

    expect($attributes['rating'])->toBe(3.5)
        ->and($attributes['reviews_count'])->toBe(2);
});

it('shapes the payload by product type', function (): void {
    publishedProduct(['name' => 'ShapeStandard', 'type' => ProductType::Standard]);
    publishedProduct(['name' => 'ShapeVariant', 'type' => ProductType::Variant]);
    publishedProduct(['name' => 'ShapeVirtual', 'type' => ProductType::Virtual]);
    publishedProduct(['name' => 'ShapeExternal', 'type' => ProductType::External, 'external_id' => 'ext-42']);

    $products = collect($this->getJson('/store/products?filter[name]=Shape')->assertOk()->json('data'))
        ->keyBy('attributes.name')
        ->map(fn (array $product): array => $product['attributes']);

    expect($products->get('ShapeStandard'))->toHaveKeys(['stock', 'in_stock'])
        ->not->toHaveKeys(['files', 'variants_stock', 'external_id'])
        ->and($products->get('ShapeVariant'))->toHaveKey('in_stock')
        ->not->toHaveKeys(['stock', 'variants_stock', 'files'])
        ->and($products->get('ShapeVirtual'))->toHaveKeys(['files', 'stock', 'in_stock'])
        ->and($products->get('ShapeExternal'))->toMatchArray(['external_id' => 'ext-42'])
        ->not->toHaveKeys(['stock', 'variants_stock', 'in_stock', 'files']);
});

it('only exposes the variants and options relationships for capable product types', function (): void {
    publishedProduct(['name' => 'RelVariant', 'type' => ProductType::Variant]);
    publishedProduct(['name' => 'RelExternal', 'type' => ProductType::External, 'external_id' => 'ext-1']);

    $products = collect(
        $this->getJson('/store/products?filter[name]=Rel&include=variants,options')->assertOk()->json('data')
    )->keyBy('attributes.name');

    expect($products->get('RelVariant')['relationships'])->toHaveKey('variants')
        ->and($products->get('RelExternal')['relationships'] ?? [])->not->toHaveKey('variants')
        ->not->toHaveKey('options');
});

it('paginates with page[size] and page[number]', function (): void {
    publishedProduct(['name' => 'PageOne']);
    publishedProduct(['name' => 'PageTwo']);
    publishedProduct(['name' => 'PageThree']);

    $first = $this->getJson('/store/products?filter[name]=Page&sort=name&page[size]=2')->assertOk();
    expect($first->json('data'))->toHaveCount(2);

    $second = $this->getJson('/store/products?filter[name]=Page&sort=name&page[size]=2&page[number]=2')->assertOk();
    expect($second->json('data'))->toHaveCount(1);
});

it('caps page[size] at the configured maximum', function (): void {
    config()->set('shopper.api.pagination.max_per_page', 1);
    publishedProduct(['name' => 'CapA']);
    publishedProduct(['name' => 'CapB']);

    $response = $this->getJson('/store/products?filter[name]=Cap&page[size]=50')->assertOk();
    expect($response->json('data'))->toHaveCount(1);
});

it('caps page[number] at the configured maximum page', function (): void {
    config()->set('shopper.api.pagination.max_page', 2);
    publishedProduct(['name' => 'DeepA']);
    publishedProduct(['name' => 'DeepB']);
    publishedProduct(['name' => 'DeepC']);

    $capped = $this->getJson('/store/products?filter[name]=Deep&sort=name&page[size]=1&page[number]=500')->assertOk();

    expect($capped->json('data'))->toHaveCount(1)
        ->and($capped->json('data.0.attributes.name'))->toBe('DeepB');
});

it('walks the whole catalog through cursor pagination without overlap', function (): void {
    publishedProduct(['name' => 'CursorA']);
    publishedProduct(['name' => 'CursorB']);
    publishedProduct(['name' => 'CursorC']);

    $names = [];
    $url = '/store/products?filter[name]=Cursor&sort=name&page[size]=2&page[cursor]=';

    do {
        $response = $this->getJson($url)->assertOk();

        foreach ($response->json('data') as $product) {
            $names[] = $product['attributes']['name'];
        }

        $url = $response->json('links.next');
    } while ($url !== null);

    expect($names)->toBe(['CursorA', 'CursorB', 'CursorC']);
});

it('filters and sorts the product list through the allowlist', function (): void {
    publishedProduct(['name' => 'ZzzCatalogBeta']);
    publishedProduct(['name' => 'ZzzCatalogAlpha']);

    $names = collect(
        $this->getJson('/store/products?filter[name]=ZzzCatalog&sort=name')->assertOk()->json('data')
    )->pluck('attributes.name');

    expect($names->toArray())->toBe(['ZzzCatalogAlpha', 'ZzzCatalogBeta']);
});
