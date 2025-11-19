<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Event;
use Livewire\Livewire;
use Shopper\Core\Enum\ProductType;
use Shopper\Core\Events\Products\Created;
use Shopper\Core\Models\Brand;
use Shopper\Core\Models\Category;
use Shopper\Core\Models\Channel;
use Shopper\Core\Models\Inventory;
use Shopper\Core\Models\Product;
use Shopper\Core\Models\User;
use Shopper\Livewire\SlideOvers\AddProduct;

uses(Tests\TestCase::class);

beforeEach(function (): void {
    $this->user = User::factory()->create();
    $this->user->givePermissionTo('add_products');
    $this->actingAs($this->user);

    Event::fake();
});

describe(AddProduct::class, function (): void {
    it('create a new product', function (): void {
        Livewire::test(AddProduct::class)
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
    });

    it('create new product with stock', function (): void {
        Inventory::factory(['is_default' => true])->create();

        Livewire::test(AddProduct::class)
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
    });

    it('create new product with associations', function (): void {
        $brand = Brand::factory(['is_enabled' => true])->create();
        $categories = Category::factory(['is_enabled' => true])->count(3)->create();
        $channels = Channel::factory(['is_enabled' => true])->count(2)->create();

        Livewire::test(AddProduct::class)
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
    });

    it('redirect to edit page after create product', function (): void {
        Livewire::test(AddProduct::class)
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
    });
})->group('livewire', 'slideovers', 'products');
