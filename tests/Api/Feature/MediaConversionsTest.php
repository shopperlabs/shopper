<?php

declare(strict_types=1);

use Illuminate\Http\UploadedFile;
use Shopper\Core\Enum\ProductType;
use Tests\Core\Stubs\Product;

uses(Tests\Api\TestCase::class);

function productWithImage(string $name): Product
{
    $product = Product::factory()->publish()->create(['name' => $name, 'type' => ProductType::Standard]);

    $product->addMedia(UploadedFile::fake()->image('shot.png', 900, 900))
        ->toMediaCollection((string) config('shopper.media.storage.collection_name'));

    return $product;
}

it('serves every generated conversion next to the original image', function (): void {
    $product = productWithImage('Camera');

    $thumbnail = $this->getJson('/store/products/'.$product->slug)
        ->assertOk()
        ->json('data.attributes.thumbnail');

    expect($thumbnail['conversions'])->toHaveKeys(['large', 'medium'])
        ->and($thumbnail['conversions']['medium'])->toContain('conversions/shot-medium.png')
        ->and($thumbnail['conversions']['medium'])->not->toBe($thumbnail['url']);
});

it('never serves a conversion that was not generated for the media', function (): void {
    config(['shopper.media.conversions' => []]);

    $product = productWithImage('Lens');

    $this->getJson('/store/products/'.$product->slug)
        ->assertOk()
        ->assertJsonPath('data.attributes.thumbnail.conversions', []);
});
