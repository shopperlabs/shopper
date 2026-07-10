<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Queue;
use Shopper\Core\Enum\ImportStatus;
use Shopper\Core\Import\ProductRow;
use Shopper\Core\Jobs\ImportProductsChunkJob;
use Shopper\Core\Models\Inventory;
use Shopper\Core\Models\Product;
use Shopper\Core\Models\ProductImport;

uses(Tests\Core\TestCase::class);

describe(ImportProductsChunkJob::class, function (): void {
    it('imports its rows and records per-row failures without failing the chunk', function (): void {
        Queue::fake();
        setupCurrencies();
        Inventory::factory()->create(['is_default' => true]);

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

        $job->handle(resolve(Shopper\Core\Import\ProductRowImporter::class));

        $import->refresh();

        expect($import->imported_count)->toBe(1)
            ->and($import->failed_count)->toBe(1)
            ->and($import->errors)->toHaveCount(1)
            ->and($import->errors[0]['handle'])->toBe('broken-product')
            ->and(Product::query()->where('slug', 'tee')->exists())->toBeTrue();
    });
});
