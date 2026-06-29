<?php

declare(strict_types=1);

use Illuminate\Http\UploadedFile;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileUnacceptableForCollection;
use Tests\Core\Stubs\Product;

uses(Tests\Core\TestCase::class);

describe('media collection mime enforcement', function (): void {
    it('rejects an executable HTML file uploaded to the product files collection', function (): void {
        $product = Product::factory()->create();

        $payload = UploadedFile::fake()->createWithContent(
            'payload.html',
            '<script>alert(document.domain)</script>',
        );

        expect(fn (): mixed => $product->addMedia($payload)->toMediaCollection('files'))
            ->toThrow(FileUnacceptableForCollection::class);
    });

    it('accepts an allowed document in the product files collection', function (): void {
        $product = Product::factory()->create();

        $document = UploadedFile::fake()->createWithContent('notes.txt', 'product manual');

        $media = $product->addMedia($document)->toMediaCollection('files');

        expect($media->collection_name)->toBe('files');
    });

    it('rejects an SVG uploaded to an image collection', function (): void {
        $product = Product::factory()->create();

        $svg = UploadedFile::fake()->createWithContent(
            'logo.svg',
            '<svg xmlns="http://www.w3.org/2000/svg"><script>alert(1)</script></svg>',
        );

        expect(fn (): mixed => $product->addMedia($svg)
            ->toMediaCollection(config('shopper.media.storage.collection_name')))
            ->toThrow(FileUnacceptableForCollection::class);
    });
})->group('media', 'security');
