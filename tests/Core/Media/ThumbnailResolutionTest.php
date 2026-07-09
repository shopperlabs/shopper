<?php

declare(strict_types=1);

use Illuminate\Http\UploadedFile;
use Tests\Core\Stubs\Product;
use Tests\Core\Stubs\ProductVariant;

uses(Tests\Core\TestCase::class);

describe('thumbnail resolution', function (): void {
    it('returns the thumbnail media when one is uploaded', function (): void {
        $product = Product::factory()->create();

        $product->addMedia(UploadedFile::fake()->image('gallery.jpg'))
            ->toMediaCollection(config('shopper.media.storage.collection_name'));
        $thumbnail = $product->addMedia(UploadedFile::fake()->image('thumb.jpg'))
            ->toMediaCollection(config('shopper.media.storage.thumbnail_collection'));

        expect($product->getThumbnail()?->id)->toBe($thumbnail->id)
            ->and($product->getThumbnailUrl())->toBe($thumbnail->getUrl());
    });

    it('falls back to the first gallery image when no thumbnail is set', function (): void {
        $product = Product::factory()->create();

        $first = $product->addMedia(UploadedFile::fake()->image('first.jpg'))
            ->toMediaCollection(config('shopper.media.storage.collection_name'));
        $product->addMedia(UploadedFile::fake()->image('second.jpg'))
            ->toMediaCollection(config('shopper.media.storage.collection_name'));

        expect($product->getThumbnail()?->id)->toBe($first->id)
            ->and($product->getThumbnailUrl())->toBe($first->getUrl());
    });

    it('returns null and the fallback url when the product has no media', function (): void {
        $product = Product::factory()->create();

        expect($product->getThumbnail())->toBeNull()
            ->and($product->getThumbnailUrl())->toBe(shopper_fallback_url());
    });

    it('resolves the thumbnail url for a named conversion', function (): void {
        $product = Product::factory()->create();

        $product->addMedia(UploadedFile::fake()->image('thumb.jpg', 1000, 1000))
            ->toMediaCollection(config('shopper.media.storage.thumbnail_collection'));

        expect($product->getThumbnailUrl('medium'))->toContain('medium');
    });

    it('resolves the variant thumbnail with the gallery fallback', function (): void {
        $product = Product::factory()->create();
        $variant = ProductVariant::factory()->create(['product_id' => $product->id]);

        $image = $variant->addMedia(UploadedFile::fake()->image('variant.jpg'))
            ->toMediaCollection(config('shopper.media.storage.collection_name'));

        expect($variant->getThumbnail()?->id)->toBe($image->id)
            ->and($variant->getThumbnailUrl())->toBe($image->getUrl());
    });
})->group('media');
