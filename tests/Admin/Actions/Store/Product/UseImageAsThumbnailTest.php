<?php

declare(strict_types=1);

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\UploadedFile;
use Shopper\Actions\Store\Product\UseImageAsThumbnail;
use Tests\Core\Stubs\Product;

uses(Tests\Admin\TestCase::class);

describe(UseImageAsThumbnail::class, function (): void {
    it('copies a gallery image into the thumbnail collection', function (): void {
        $product = Product::factory()->create();
        $image = $product->addMedia(UploadedFile::fake()->image('gallery.jpg'))
            ->toMediaCollection(config('shopper.media.storage.collection_name'));

        $thumbnail = app()->call(UseImageAsThumbnail::class, [
            'model' => $product,
            'mediaId' => $image->id,
        ]);

        expect($thumbnail->collection_name)->toBe(config('shopper.media.storage.thumbnail_collection'))
            ->and($thumbnail->file_name)->toBe($image->file_name)
            ->and($product->getMedia(config('shopper.media.storage.collection_name')))->toHaveCount(1);
    });

    it('replaces the previous thumbnail', function (): void {
        $product = Product::factory()->create();
        $product->addMedia(UploadedFile::fake()->image('old-thumb.jpg'))
            ->toMediaCollection(config('shopper.media.storage.thumbnail_collection'));
        $image = $product->addMedia(UploadedFile::fake()->image('new.jpg'))
            ->toMediaCollection(config('shopper.media.storage.collection_name'));

        app()->call(UseImageAsThumbnail::class, [
            'model' => $product,
            'mediaId' => $image->id,
        ]);

        $thumbnails = $product->refresh()->getMedia(config('shopper.media.storage.thumbnail_collection'));

        expect($thumbnails)->toHaveCount(1)
            ->and($thumbnails->first()->file_name)->toBe($image->file_name);
    });

    it('rejects a media that does not belong to the model gallery', function (): void {
        $product = Product::factory()->create();
        $other = Product::factory()->create();
        $foreign = $other->addMedia(UploadedFile::fake()->image('foreign.jpg'))
            ->toMediaCollection(config('shopper.media.storage.collection_name'));

        expect(fn (): mixed => app()->call(UseImageAsThumbnail::class, [
            'model' => $product,
            'mediaId' => $foreign->id,
        ]))->toThrow(AuthorizationException::class);
    });
})->group('actions', 'product', 'media');
