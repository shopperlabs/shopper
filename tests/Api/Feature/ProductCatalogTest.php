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
use Shopper\Core\Models\Collection;
use Shopper\Core\Models\Contracts\AttributeProduct as AttributeProductContract;
use Shopper\Core\Models\Currency;
use Shopper\Core\Models\Inventory;
use Shopper\Core\Models\Price;
use Shopper\Core\Models\Product;
use Shopper\Core\Models\ProductVariant;
use Shopper\Core\Models\Review;

uses(Tests\Api\TestCase::class);

function publishedProduct(array $attributes = [], int $amount = 2999): Product
{
    $product = Product::factory()->publish()->create($attributes + ['type' => ProductType::Standard]);

    Price::factory()->create([
        'priceable_id' => $product->id,
        'priceable_type' => $product->getMorphClass(),
        'currency_id' => Currency::query()->where('code', shopper_currency())->value('id'),
        'amount' => $amount,
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
    $brand = Brand::factory()->create(['is_enabled' => true]);
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

it('filters products by category subtree', function (): void {
    $furniture = Category::factory()->create(['name' => 'Furniture', 'slug' => 'furniture']);
    $sofas = Category::factory()->create(['name' => 'Sofas', 'parent_id' => $furniture->id]);
    $corner = Category::factory()->create(['name' => 'Corner', 'parent_id' => $sofas->id]);
    $garden = Category::factory()->create(['name' => 'Garden', 'slug' => 'garden']);
    $pots = Category::factory()->create(['name' => 'Pots', 'parent_id' => $garden->id]);

    $direct = publishedProduct(['name' => 'Bookshelf']);
    $direct->categories()->attach($furniture);

    $deep = publishedProduct(['name' => 'Corner Sofa']);
    $deep->categories()->attach($corner);

    $foreign = publishedProduct(['name' => 'Flower Pot']);
    $foreign->categories()->attach($pots);

    $names = collect($this->getJson('/store/products?filter[category_tree]=furniture')->assertOk()->json('data'))
        ->pluck('attributes.name');

    expect($names)->toContain('Bookshelf')
        ->and($names)->toContain('Corner Sofa')
        ->and($names)->not->toContain('Flower Pot');
});

it('returns no products for an unknown category subtree', function (): void {
    publishedProduct(['name' => 'Pixel']);

    $this->getJson('/store/products?filter[category_tree]=nope')
        ->assertOk()
        ->assertJsonCount(0, 'data');
});

it('filters products by category subtree through the public identifier', function (): void {
    $furniture = Category::factory()->create(['name' => 'Furniture', 'slug' => 'furniture']);
    $sofas = Category::factory()->create(['name' => 'Sofas', 'parent_id' => $furniture->id]);

    publishedProduct(['name' => 'Bookshelf'])->categories()->attach($sofas);
    publishedProduct(['name' => 'Flower Pot']);

    $names = collect($this->getJson('/store/products?filter[category_tree]='.$furniture->public_id)->assertOk()->json('data'))
        ->pluck('attributes.name');

    expect($names)->toContain('Bookshelf')->and($names)->not->toContain('Flower Pot');
});

it('hides the products of a disabled category subtree', function (): void {
    $furniture = Category::factory()->create(['name' => 'Furniture', 'slug' => 'furniture']);
    $unreleased = Category::factory()->create(['name' => 'Unreleased', 'parent_id' => $furniture->id]);

    publishedProduct(['name' => 'Secret Sofa'])->categories()->attach($unreleased);
    publishedProduct(['name' => 'Bookshelf'])->categories()->attach($furniture);

    $unreleased->update(['is_enabled' => false]);

    $names = collect($this->getJson('/store/products?filter[category_tree]='.$furniture->slug)->assertOk()->json('data'))
        ->pluck('attributes.name');

    expect($names)->toContain('Bookshelf')->and($names)->not->toContain('Secret Sofa');

    $this->getJson('/store/products?filter[category_tree]='.$unreleased->slug)
        ->assertOk()
        ->assertJsonCount(0, 'data');
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
        ->and($products->get('ShapeExternal'))->toMatchArray(['external_id' => 'ext-42', 'in_stock' => true])
        ->not->toHaveKeys(['stock', 'variants_stock', 'files']);
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

it('lists the products matching any value of a multi-valued facet', function (): void {
    $color = Attribute::factory()->create(['name' => 'Color']);
    $red = AttributeValue::factory()->create(['attribute_id' => $color->id, 'value' => 'Red', 'key' => 'red']);
    $blue = AttributeValue::factory()->create(['attribute_id' => $color->id, 'value' => 'Blue', 'key' => 'blue']);

    $both = publishedProduct(['name' => 'FacetBoth', 'type' => ProductType::Variant]);
    ProductVariant::factory()->create(['product_id' => $both->id])->values()->attach([$red->id, $blue->id]);

    $blueOnly = publishedProduct(['name' => 'FacetBlue', 'type' => ProductType::Variant]);
    ProductVariant::factory()->create(['product_id' => $blueOnly->id])->values()->attach($blue->id);

    publishedProduct(['name' => 'FacetNone', 'type' => ProductType::Variant]);

    $names = collect($this->getJson('/store/products?filter[option]=red,blue')->assertOk()->json('data'))
        ->pluck('attributes.name')
        ->sort()
        ->values();

    expect($names->all())->toBe(['FacetBlue', 'FacetBoth']);
});

it('keeps two different facets an intersection', function (): void {
    $nordika = Brand::factory()->create(['name' => 'Nordika', 'slug' => 'nordika', 'is_enabled' => true]);
    $hem = Brand::factory()->create(['name' => 'Hem', 'slug' => 'hem', 'is_enabled' => true]);

    $color = Attribute::factory()->create(['name' => 'Color']);
    $red = AttributeValue::factory()->create(['attribute_id' => $color->id, 'value' => 'Red', 'key' => 'red']);

    $match = publishedProduct(['name' => 'IntersectHit', 'type' => ProductType::Variant, 'brand_id' => $nordika->id]);
    ProductVariant::factory()->create(['product_id' => $match->id])->values()->attach($red->id);

    $otherBrand = publishedProduct(['name' => 'IntersectMiss', 'type' => ProductType::Variant, 'brand_id' => $hem->id]);
    ProductVariant::factory()->create(['product_id' => $otherBrand->id])->values()->attach($red->id);

    $names = collect(
        $this->getJson('/store/products?filter[option]=red&filter[brand]=nordika,unknown')->assertOk()->json('data')
    )->pluck('attributes.name');

    expect($names->all())->toBe(['IntersectHit']);
});

it('matches an option declared on a product that has no variants', function (): void {
    $product = publishedProduct(['name' => 'StandardOption', 'type' => ProductType::Standard]);
    $color = Attribute::factory()->create(['name' => 'Color']);
    $green = AttributeValue::factory()->create(['attribute_id' => $color->id, 'value' => 'Green', 'key' => 'green']);
    $product->options()->attach($color->id, ['attribute_value_id' => $green->id]);

    publishedProduct(['name' => 'StandardPlain', 'type' => ProductType::Standard]);

    $names = collect($this->getJson('/store/products?filter[option]=green')->assertOk()->json('data'))
        ->pluck('attributes.name');

    expect($names->all())->toBe(['StandardOption']);
});

it('never contradicts the in_stock attribute of the rows it filters', function (): void {
    $inventory = Inventory::factory()->create();

    publishedProduct(['name' => 'StockEmpty', 'type' => ProductType::Standard]);
    publishedProduct(['name' => 'StockBackorder', 'type' => ProductType::Standard, 'allow_backorder' => true]);
    publishedProduct(['name' => 'StockExternal', 'type' => ProductType::External]);
    publishedProduct(['name' => 'StockLegacy', 'type' => null]);
    publishedProduct(['name' => 'StockFilled', 'type' => ProductType::Standard])->mutateStock($inventory->id, 4);
    publishedProduct(['name' => 'StockVirtual', 'type' => ProductType::Virtual])->mutateStock($inventory->id, 1);
    publishedProduct(['name' => 'StockVariantEmpty', 'type' => ProductType::Variant]);

    $negative = publishedProduct(['name' => 'StockNegative', 'type' => ProductType::Standard]);
    $negative->mutateStock($inventory->id, 2);
    $negative->decreaseStock($inventory->id, 5);

    $variantProduct = publishedProduct(['name' => 'StockVariant', 'type' => ProductType::Variant]);
    ProductVariant::factory()->create(['product_id' => $variantProduct->id])->mutateStock($inventory->id, 2);

    $all = collect($this->getJson('/store/products?page[size]=50')->assertOk()->json('data'));
    $available = collect($this->getJson('/store/products?filter[in_stock]=1&page[size]=50')->assertOk()->json('data'));
    $unavailable = collect($this->getJson('/store/products?filter[in_stock]=false&page[size]=50')->assertOk()->json('data'));

    expect($available->pluck('attributes.name')->sort()->values()->all())
        ->toBe(['StockBackorder', 'StockExternal', 'StockFilled', 'StockVariant', 'StockVirtual'])
        ->and($unavailable->pluck('attributes.name')->sort()->values()->all())
        ->toBe(['StockEmpty', 'StockLegacy', 'StockNegative', 'StockVariantEmpty'])
        ->and($available->count() + $unavailable->count())->toBe($all->count())
        ->and($available->firstWhere('attributes.name', 'StockExternal')['attributes']['in_stock'])->toBeTrue()
        ->and($available->pluck('attributes.in_stock')->filter(fn (?bool $value): bool => $value === false))->toBeEmpty()
        ->and($unavailable->pluck('attributes.in_stock')->filter(fn (?bool $value): bool => $value === true))->toBeEmpty();
});

it('hides the products reached through a disabled category, brand or unpublished collection facet', function (): void {
    $staged = Category::factory()->create(['name' => 'Staged', 'slug' => 'staged', 'is_enabled' => false]);
    publishedProduct(['name' => 'StagedProduct'])->categories()->attach($staged);

    $ghost = Brand::factory()->create(['name' => 'Ghost', 'slug' => 'ghost', 'is_enabled' => false]);
    publishedProduct(['name' => 'GhostProduct', 'brand_id' => $ghost->id]);

    $embargoed = Collection::factory()->create(['name' => 'Embargoed', 'slug' => 'embargoed', 'published_at' => now()->addYear()]);
    publishedProduct(['name' => 'EmbargoedProduct'])->collections()->attach($embargoed->id);

    $this->getJson('/store/products?filter[category]=staged')->assertOk()->assertJsonCount(0, 'data');
    $this->getJson('/store/products?filter[brand]=ghost')->assertOk()->assertJsonCount(0, 'data');
    $this->getJson('/store/products?filter[collection]=embargoed')->assertOk()->assertJsonCount(0, 'data');
});

it('unions the subtrees of two category_tree values and skips an unknown one', function (): void {
    $furniture = Category::factory()->create(['name' => 'Furniture', 'slug' => 'furniture']);
    $garden = Category::factory()->create(['name' => 'Garden', 'slug' => 'garden']);

    publishedProduct(['name' => 'Bookshelf'])->categories()->attach($furniture);
    publishedProduct(['name' => 'FlowerPot'])->categories()->attach($garden);
    publishedProduct(['name' => 'Standalone']);

    $names = collect(
        $this->getJson('/store/products?filter[category_tree]=furniture,garden,nope')->assertOk()->json('data')
    )->pluck('attributes.name')->sort()->values();

    expect($names->all())->toBe(['Bookshelf', 'FlowerPot']);
});

it('refuses a filter carrying more values than the allowed breadth', function (): void {
    publishedProduct(['name' => 'Pixel']);

    $this->getJson('/store/products?filter[option]='.implode(',', range(1, 51)))->assertStatus(422);

    $this->getJson('/store/products?filter[option]='.implode(',', range(1, 50)))
        ->assertOk()
        ->assertJsonCount(0, 'data');
});

it('bounds the listing prices on the result before the price filters narrow it', function (): void {
    publishedProduct(['name' => 'BoundCheap'], amount: 1000);
    publishedProduct(['name' => 'BoundMid'], amount: 5000);
    publishedProduct(['name' => 'BoundPricey'], amount: 245000);

    $response = $this->getJson('/store/products?include=price_range&filter[price_min]=4000')->assertOk();

    expect($response->json('meta.price_range'))->toBe([
        'min' => 1000,
        'max' => 245000,
        'currency_code' => $response->json('meta.currency'),
    ])
        ->and(collect($response->json('data'))->pluck('attributes.name')->sort()->values()->all())
        ->toBe(['BoundMid', 'BoundPricey']);
});

it('bounds the listing prices within the other filters and omits them unless asked', function (): void {
    $nordika = Brand::factory()->create(['name' => 'Nordika', 'slug' => 'nordika', 'is_enabled' => true]);

    publishedProduct(['name' => 'ScopedIn', 'brand_id' => $nordika->id], amount: 1000);
    publishedProduct(['name' => 'ScopedOut'], amount: 900000);

    $scoped = $this->getJson('/store/products?include=price_range&filter[brand]=nordika')->assertOk();

    expect($scoped->json('meta.price_range.min'))->toBe(1000)
        ->and($scoped->json('meta.price_range.max'))->toBe(1000)
        ->and($this->getJson('/store/products')->assertOk()->json('meta'))->not->toHaveKey('price_range');
});

it('returns a null price range when nothing in the result carries a price', function (): void {
    Product::factory()->publish()->create(['name' => 'Unpriced', 'type' => ProductType::Standard]);

    $this->getJson('/store/products?include=price_range&filter[name]=Unpriced')
        ->assertOk()
        ->assertJsonPath('meta.price_range', null);
});
