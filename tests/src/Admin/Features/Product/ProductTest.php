<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Event;
use Shopper\Core\Enum\ProductType;
use Shopper\Core\Events\Products\Created;
use Shopper\Core\Events\Products\Updated;
use Shopper\Core\Models\Brand;
use Shopper\Core\Models\Category;
use Shopper\Core\Models\Channel;
use Shopper\Core\Models\Inventory;
use Shopper\Core\Models\Product;
use Shopper\Livewire\Components\Products\Form\Edit;
use Shopper\Livewire\Pages;
use Shopper\Livewire\SlideOvers\AddProduct;
use Shopper\Tests\Admin\Features\TestCase;

use function Pest\Livewire\livewire;

uses(TestCase::class);

beforeEach(function (): void {
    Event::fake();
});

it('can render products page', function (): void {
    livewire(Pages\Product\Index::class)
        ->assertSee(__('shopper::pages/products.menu'));
})->group('product');

it('create a new product', function (): void {
    livewire(AddProduct::class)
        ->fillForm([
            'type' => ProductType::Variant(),
            'name' => fake()->name(),
            'slug' => fake()->unique()->slug(),
            'is_visible' => true,
            'published_at' => now(),
        ])
        ->call('store')
        ->assertHasNoFormErrors();

    Event::assertDispatched(Created::class);

    expect(Product::query()->count())->toBe(1);
})->group('product');

it('create new product with stock', function (): void {
    Inventory::factory(['is_default' => true])->create();

    livewire(AddProduct::class)
        ->fillForm([
            'type' => ProductType::Variant(),
            'name' => fake()->name(),
            'slug' => fake()->unique()->slug(),
            'is_visible' => true,
            'published_at' => now(),
            'quantity' => 10,
            'security_stock' => 3,
            'channels' => [],
            'categories' => null,
            'brand_id' => null,
        ])
        ->call('store')
        ->assertHasNoFormErrors();

    $product = Product::query()->first();

    Event::assertDispatched(Created::class);

    expect(Product::query()->count())
        ->toBe(1)
        ->and($product->stock)
        ->toBe(10);
})->group('product');

it('create new product with associations', function (): void {
    $brand = Brand::factory(['is_enabled' => true])->create();
    $categories = Category::factory(['is_enabled' => true])->count(3)->create();
    $channels = Channel::factory(['is_enabled' => true])->count(2)->create();

    livewire(AddProduct::class)
        ->fillForm([
            'type' => ProductType::Variant(),
            'name' => fake()->name(),
            'slug' => fake()->unique()->slug(),
            'is_visible' => true,
            'published_at' => now(),
            'channels' => $channels->pluck('id')->toArray(),
            'categories' => $categories->pluck('id')->toArray(),
            'brand_id' => $brand->id,
        ])
        ->call('store')
        ->assertHasNoFormErrors();

    Event::assertDispatched(Created::class);

    $product = Product::query()->first();

    expect(Product::query()->count())
        ->toBe(1)
        ->and($product->brand)->toBeInstanceOf(Brand::class)
        ->and($product->categories->count())->toBe(3)
        ->and($product->channels->count())->toBe(2);
})->group('product');

it('redirect to edit page after create product', function (): void {
    livewire(AddProduct::class)
        ->fillForm([
            'type' => ProductType::Variant(),
            'name' => fake()->name(),
            'slug' => fake()->unique()->slug(),
            'is_visible' => true,
            'published_at' => now(),
        ])
        ->call('store')
        ->assertHasNoFormErrors()
        ->assertRedirect(route('shopper.products.edit', Product::query()->first()));
})->group('product');

it('can update product information', function (): void {
    $product = Product::factory()->create();

    livewire(Edit::class, ['product' => $product])
        ->fillForm([
            'name' => 'Demo product',
        ])
        ->call('store')
        ->assertHasNoFormErrors();

    $product->refresh();

    Event::assertDispatched(Updated::class);

    expect($product->slug)->toBe('demo-product');
})->group('product');

it('ensure that external_id field is invisible on non external product', function (): void {
    $product = Product::factory()->virtual()->create();

    livewire(Edit::class, ['product' => $product])
        ->fillForm()
        ->assertFormFieldIsHidden('external_id');
})->group('product');

it('can view the external id field on external product editing', function (): void {
    $product = Product::factory()->external()->create();

    livewire(Edit::class, ['product' => $product])
        ->fillForm([
            'external_id' => $uuid = fake()->uuid,
        ])
        ->assertFormFieldIsVisible('external_id')
        ->call('store')
        ->assertHasNoFormErrors();

    $product->refresh();

    Event::assertDispatched(Updated::class);

    expect($product->external_id)->toBe($uuid);
})->group('product');
