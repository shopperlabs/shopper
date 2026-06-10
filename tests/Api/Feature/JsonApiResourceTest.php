<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Shopper\Core\Models\Contracts\Product as ProductContract;
use Shopper\Core\Models\Product;
use Tests\Api\Stubs\ProductStubResource;

uses(Tests\Api\TestCase::class);

beforeEach(function (): void {
    Route::get(
        '/__test/products/{id}',
        fn (string $id) => ProductStubResource::make(resolve(ProductContract::class)::query()->findOrFail((int) $id))
    );
});

it('serializes a model as a JSON:API document', function (): void {
    $product = Product::factory()->create(['name' => 'Tee']);

    $this->getJson('/__test/products/'.$product->getKey())
        ->assertOk()
        ->assertJsonPath('data.type', 'products')
        ->assertJsonPath('data.id', $product->public_id)
        ->assertJsonPath('data.attributes.name', 'Tee')
        ->assertJsonStructure(['data' => ['type', 'id', 'attributes' => ['name', 'slug']]]);
});

it('respects sparse fieldsets', function (): void {
    $product = Product::factory()->create(['name' => 'Tee']);

    $this->getJson('/__test/products/'.$product->getKey().'?fields[products]=name')
        ->assertOk()
        ->assertJsonPath('data.attributes.name', 'Tee')
        ->assertJsonMissingPath('data.attributes.slug');
});
