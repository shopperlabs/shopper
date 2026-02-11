<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Shopper\Core\Models\Product;
use Shopper\Core\Models\ProductTag;

uses(Tests\TestCase::class);

describe(ProductTag::class, function (): void {
    it('uses correct table name', function (): void {
        $tag = new ProductTag;

        expect($tag->getTable())->toBe(shopper_table('product_tags'));
    });

    it('has correct attributes', function (): void {
        $tag = ProductTag::factory()->create([
            'name' => 'Summer',
            'slug' => 'summer',
        ]);

        expect($tag->name)->toBe('Summer')
            ->and($tag->slug)->toBe('summer');
    });

    it('has products relationship', function (): void {
        $tag = ProductTag::factory()->create();

        expect($tag->products())->toBeInstanceOf(MorphToMany::class);
    });

    it('can be attached to products', function (): void {
        $tag = ProductTag::factory()->create();
        $products = Product::factory()->count(3)->create();

        $tag->products()->attach($products->pluck('id'));

        expect($tag->products()->count())->toBe(3);
    });

    it('can be accessed from product', function (): void {
        $product = Product::factory()->create();
        $tags = ProductTag::factory()->count(2)->create();

        $product->tags()->attach($tags->pluck('id'));

        expect($product->tags()->count())->toBe(2)
            ->and($product->tags->first())->toBeInstanceOf(ProductTag::class);
    });
})->group('product-tag', 'models');
