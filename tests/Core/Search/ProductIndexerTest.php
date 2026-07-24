<?php

declare(strict_types=1);

use Shopper\Core\Models\Brand;
use Shopper\Core\Models\Category;
use Shopper\Core\Models\Collection;
use Shopper\Core\Models\Order;
use Shopper\Core\Models\Product;
use Shopper\Core\Search\CustomerIndexer;
use Shopper\Core\Search\ProductIndexer;
use Shopper\Core\Search\ScoutIndexer;
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

it('builds the searchable payload with brand and categories', function (): void {
    $brand = Brand::factory()->create(['name' => 'Nike']);
    $category = Category::factory()->create(['name' => 'Shoes', 'is_enabled' => true]);

    $product = Product::factory()->publish()->create([
        'name' => 'Air Max',
        'brand_id' => $brand->id,
        'description' => '<p>Great <strong>shoes</strong></p>',
    ]);
    $product->categories()->attach($category);

    $payload = $product->refresh()->toSearchableArray();

    expect($payload)
        ->id->toBe((string) $product->id)
        ->name->toBe('Air Max')
        ->brand->toBe('Nike')
        ->categories->toBe(['Shoes'])
        ->description->toBe('Great shoes')
        ->and($payload['published_at'])->toBeInt();
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
        ->and($scheduled->shouldBeSearchable())->toBeFalse();
});

it('builds the order searchable payload', function (): void {
    $order = Order::factory()->create();

    $payload = $order->toSearchableArray();

    expect($payload)
        ->id->toBe((string) $order->id)
        ->number->toBe($order->number)
        ->status->toBe($order->status->value)
        ->and($payload['created_at'])->toBeInt();
});

it('resolves the customer indexer for the configured user model', function (): void {
    $user = User::factory()->create();

    expect($user->indexer())->toBeInstanceOf(CustomerIndexer::class)
        ->and($user->toSearchableArray())
        ->email->toBe($user->email)
        ->id->toBe((string) $user->id);
});
