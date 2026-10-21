<?php

declare(strict_types=1);

use Illuminate\Support\Facades\DB;
use Shopper\Core\Models\Brand;
use Shopper\Core\Models\Category;
use Shopper\Core\Models\Channel;
use Shopper\Core\Models\Collection;
use Shopper\Core\Models\Order;
use Shopper\Core\Models\Product;
use Shopper\Core\Models\ProductVariant;
use Shopper\Core\Search\CustomerIndexer;
use Shopper\Core\Search\ProductIndexer;
use Shopper\Core\Search\ScoutIndexer;
use Tests\Core\Stubs\RecordingEngine;
use Tests\Core\Stubs\User;

uses(Tests\Core\TestCase::class);

it('resolves the product indexer from config', function (): void {
    $product = Product::factory()->create();

    expect($product->indexer())->toBeInstanceOf(ProductIndexer::class);
});

it('falls back to the base indexer for unmapped models', function (): void {
    config(['shopper.search.indexers' => []]);

    $product = Product::factory()->create();

    expect($product->indexer())->toBeInstanceOf(ScoutIndexer::class);
});

it('builds the searchable payload with brand, categories and channels', function (): void {
    $brand = Brand::factory()->create(['name' => 'Nike']);
    $category = Category::factory()->create(['name' => 'Shoes', 'is_enabled' => true]);
    $channel = Channel::factory()->create();

    $product = Product::factory()->publish()->create([
        'name' => 'Air Max',
        'brand_id' => $brand->id,
        'description' => '<p>Great <strong>shoes</strong></p>',
    ]);
    $product->categories()->attach($category);
    $product->channels()->attach($channel);

    $payload = $product->refresh()->toSearchableArray();

    expect($payload)
        ->id->toBe($product->public_id)
        ->name->toBe('Air Max')
        ->brand->toBe('Nike')
        ->categories->toBe(['Shoes'])
        ->channels->toBe([$channel->public_id])
        ->description->toBe('Great shoes')
        ->and($payload['published_at'])->toBeInt();
});

it('leaves disabled brands and hidden categories out of the product payload', function (): void {
    $brand = Brand::factory()->create(['is_enabled' => false]);
    $visible = Category::factory()->create(['name' => 'Shoes', 'is_enabled' => true]);
    $disabled = Category::factory()->create(['name' => 'Archive', 'is_enabled' => false]);
    $orphan = Category::factory()->create(['name' => 'Sneakers', 'is_enabled' => true, 'parent_id' => $disabled->id]);

    $product = Product::factory()->publish()->create(['brand_id' => $brand->id]);
    $product->categories()->attach([$visible->id, $disabled->id, $orphan->id]);

    expect($product->refresh()->toSearchableArray())
        ->brand->toBeNull()
        ->categories->toBe(['Shoes']);
});

it('finds indexed products by their public id', function (): void {
    config(['scout.driver' => 'collection']);

    $product = Product::factory()->publish()->create(['name' => 'Air Max']);

    expect($product->getScoutKeyName())->toBe('public_id')
        ->and(Product::search('Air Max')->get()->modelKeys())->toBe([$product->id]);
});

it('only marks visible products as searchable', function (): void {
    $visible = Product::factory()->publish()->create();
    $hidden = Product::factory()->create(['is_visible' => false]);

    expect($visible->shouldBeSearchable())->toBeTrue()
        ->and($hidden->shouldBeSearchable())->toBeFalse();
});

it('does not mark a scheduled product as searchable before its publish date', function (): void {
    $scheduled = Product::factory()->create([
        'is_visible' => true,
        'published_at' => now()->addWeek(),
    ]);

    expect($scheduled->shouldBeSearchable())->toBeFalse();
});

it('does not override an indexer already configured for the user model', function (): void {
    $userModel = (string) config('auth.providers.users.model');
    config(['shopper.search.indexers.'.$userModel => 'App\CustomCustomerIndexer']);

    (new Shopper\Core\CoreServiceProvider(app()))->packageBooted();

    expect(config('shopper.search.indexers.'.$userModel))->toBe('App\CustomCustomerIndexer');
});

it('only marks enabled brands and categories as searchable', function (): void {
    $enabledBrand = Brand::factory()->create(['is_enabled' => true]);
    $disabledBrand = Brand::factory()->create(['is_enabled' => false]);
    $enabledCategory = Category::factory()->create(['is_enabled' => true]);
    $disabledCategory = Category::factory()->create(['is_enabled' => false]);

    expect($enabledBrand->shouldBeSearchable())->toBeTrue()
        ->and($disabledBrand->shouldBeSearchable())->toBeFalse()
        ->and($enabledCategory->shouldBeSearchable())->toBeTrue()
        ->and($disabledCategory->shouldBeSearchable())->toBeFalse();
});

it('only marks published collections as searchable', function (): void {
    $published = Collection::factory()->create(['published_at' => now()->subDay()]);
    $scheduled = Collection::factory()->create(['published_at' => now()->addDay()]);

    expect($published->shouldBeSearchable())->toBeTrue()
        ->and($scheduled->shouldBeSearchable())->toBeFalse()
        ->and(Collection::factory()->make(['published_at' => null])->shouldBeSearchable())->toBeFalse();
});

it('builds the order searchable payload', function (): void {
    $order = Order::factory()->create();

    $payload = $order->toSearchableArray();

    expect($payload)
        ->id->toBe($order->public_id)
        ->number->toBe($order->number)
        ->status->toBe($order->status->value)
        ->and($payload['created_at'])->toBeInt();
});

it('resolves the customer indexer for the configured user model', function (): void {
    $user = User::factory()->create();

    expect($user->indexer())->toBeInstanceOf(CustomerIndexer::class)
        ->and($user->toSearchableArray())
        ->email->toBe($user->email)
        ->id->toBe($user->public_id);
});

it('reindexes the products and child categories of a category that gets disabled', function (): void {
    $engine = RecordingEngine::register();
    $shoes = Category::factory()->create(['name' => 'Shoes', 'is_enabled' => true]);
    $sneakers = Category::factory()->create(['name' => 'Sneakers', 'is_enabled' => true, 'parent_id' => $shoes->id]);

    $product = Product::factory()->publish()->create();
    $product->categories()->attach([$shoes->id, $sneakers->id]);
    $product->searchable();

    expect($engine->documents)->toHaveKey($sneakers->public_id);

    $shoes->updateStatus(false);

    expect($engine->documents[$product->public_id]['categories'])->toBe([])
        ->and($engine->documents)->not->toHaveKey($shoes->public_id)
        ->and($engine->documents)->not->toHaveKey($sneakers->public_id);
});

it('reindexes the products and child categories of a deleted category', function (): void {
    $engine = RecordingEngine::register();
    $shoes = Category::factory()->create(['name' => 'Shoes', 'is_enabled' => true]);
    $archive = Category::factory()->create(['name' => 'Archive', 'is_enabled' => false]);
    $sneakers = Category::factory()->create(['name' => 'Sneakers', 'is_enabled' => true, 'parent_id' => $archive->id]);

    $product = Product::factory()->publish()->create();
    $product->categories()->attach([$shoes->id, $sneakers->id]);
    $product->searchable();

    expect($engine->documents[$product->public_id]['categories'])->toBe(['Shoes'])
        ->and($engine->documents)->not->toHaveKey($sneakers->public_id);

    $shoes->delete();
    $archive->delete();

    expect($engine->documents[$product->public_id]['categories'])->toBe(['Sneakers'])
        ->and($engine->documents)->toHaveKey($sneakers->public_id);
});

it('reindexes the products of a brand that gets disabled or deleted', function (): void {
    $engine = RecordingEngine::register();
    $nike = Brand::factory()->create(['name' => 'Nike', 'is_enabled' => true]);
    $adidas = Brand::factory()->create(['name' => 'Adidas', 'is_enabled' => true]);
    $airMax = Product::factory()->publish()->create(['brand_id' => $nike->id]);
    $superstar = Product::factory()->publish()->create(['brand_id' => $adidas->id]);

    expect($engine->documents[$airMax->public_id]['brand'])->toBe('Nike');

    $nike->updateStatus(false);
    $adidas->delete();

    expect($engine->documents[$airMax->public_id]['brand'])->toBeNull()
        ->and($engine->documents[$superstar->public_id]['brand'])->toBeNull();
});

it('reindexes a product when its variants change', function (): void {
    $engine = RecordingEngine::register();
    $product = Product::factory()->publish()->create();

    $variant = ProductVariant::factory()->create(['product_id' => $product->id, 'sku' => 'AIR-42']);

    expect($engine->documents[$product->public_id]['skus'])->toBe(['AIR-42']);

    $variant->update(['sku' => 'AIR-43']);

    expect($engine->documents[$product->public_id]['skus'])->toBe(['AIR-43']);

    $variant->delete();

    expect($engine->documents[$product->public_id]['skus'])->toBe([]);
});

it('leaves the product document untouched when the variant transaction rolls back', function (): void {
    $engine = RecordingEngine::register();
    $product = Product::factory()->publish()->create();

    rescue(fn () => DB::transaction(function () use ($product): void {
        ProductVariant::factory()->create(['product_id' => $product->id, 'sku' => 'AIR-42']);

        throw new RuntimeException('Rolled back.');
    }), report: false);

    expect($engine->documents[$product->public_id]['skus'])->toBe([]);
});
