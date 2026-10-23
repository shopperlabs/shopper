<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Queue;
use Shopper\Core\Enum\ImportStatus;
use Shopper\Core\Import\ImageRow;
use Shopper\Core\Import\ProductRow;
use Shopper\Core\Import\ProductRowImporter;
use Shopper\Core\Jobs\DownloadProductImageJob;
use Shopper\Core\Jobs\ImportProductsChunkJob;
use Shopper\Core\Models\Inventory;
use Shopper\Core\Models\Product;
use Shopper\Core\Models\ProductImport;

uses(Tests\Core\TestCase::class);

beforeEach(function (): void {
    Queue::fake();
    setupCurrencies();
    Inventory::factory()->create(['is_default' => true]);
});

describe(ImportProductsChunkJob::class, function (): void {
    it('imports its rows and records per-row failures without failing the chunk', function (): void {
        $import = ProductImport::query()->create([
            'source' => 'csv',
            'disk' => 'local',
            'file_path' => 'imports/products.csv',
            'status' => ImportStatus::Processing,
        ]);

        $job = new ImportProductsChunkJob($import->id, [
            new ProductRow(handle: 'tee', name: 'Tee'),
            new ProductRow(handle: 'broken-product', name: ''),
        ]);

        $job->handle(resolve(ProductRowImporter::class));

        $import->refresh();

        expect($import->imported_count)->toBe(1)
            ->and($import->failed_count)->toBe(1)
            ->and($import->errors)->toHaveCount(1)
            ->and($import->errors[0]['handle'])->toBe('broken-product')
            ->and(Product::query()->where('slug', 'tee')->exists())->toBeTrue();
    });

    it('adds the image downloads to its batch', function (): void {
        [$job, $batch] = (new ImportProductsChunkJob(1, [
            new ProductRow(handle: 'tee', name: 'Tee', images: [new ImageRow('https://cdn.test/tee.png')]),
        ]))->withFakeBatch();

        $job->handle(resolve(ProductRowImporter::class));

        expect($batch->added)->toHaveCount(1)
            ->and($batch->added[0])->toBeInstanceOf(DownloadProductImageJob::class);

        Queue::assertNotPushed(DownloadProductImageJob::class);
    });

    it('skips its rows once its import has failed', function (): void {
        $import = ProductImport::query()->create([
            'source' => 'csv',
            'disk' => 'local',
            'file_path' => 'imports/products.csv',
            'status' => ImportStatus::Failed,
        ]);

        (new ImportProductsChunkJob($import->id, [new ProductRow(handle: 'tee', name: 'Tee')]))
            ->handle(resolve(ProductRowImporter::class));

        expect(Product::query()->where('slug', 'tee')->exists())->toBeFalse();
    });
});
