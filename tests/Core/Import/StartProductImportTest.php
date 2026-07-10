<?php

declare(strict_types=1);

use Illuminate\Bus\PendingBatch;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Storage;
use Shopper\Core\Enum\ImportStatus;
use Shopper\Core\Import\StartProductImport;
use Shopper\Core\Models\ProductImport;

uses(Tests\Core\TestCase::class);

function pendingImport(string $fixture = 'products.csv'): ProductImport
{
    Storage::fake('local');
    Storage::disk('local')->put('imports/products.csv', file_get_contents(__DIR__.'/fixtures/'.$fixture));

    return ProductImport::query()->create([
        'source' => 'csv',
        'disk' => 'local',
        'file_path' => 'imports/products.csv',
        'status' => ImportStatus::Pending,
    ]);
}

describe(StartProductImport::class, function (): void {
    it('dispatches one batch of chunked jobs and marks the import as processing', function (): void {
        Bus::fake();
        config()->set('shopper.core.import.chunk_size', 2);

        $import = pendingImport();

        resolve(StartProductImport::class)->execute($import);

        $import->refresh();

        expect($import->status)->toBe(ImportStatus::Processing)
            ->and($import->total_products)->toBe(3)
            ->and($import->started_at)->not->toBeNull();

        Bus::assertBatched(fn (PendingBatch $batch): bool => $batch->jobs->count() === 2
            && $batch->name === "product-import-{$import->id}");
    });

    it('applies the stored column mapping when reading the file', function (): void {
        Bus::fake();

        Storage::fake('local');
        Storage::disk('local')->put('imports/products.csv', file_get_contents(__DIR__.'/fixtures/custom_headers.csv'));

        $import = ProductImport::query()->create([
            'source' => 'csv',
            'disk' => 'local',
            'file_path' => 'imports/products.csv',
            'mapping' => ['handle' => 'Reference', 'name' => 'Product Title'],
            'status' => ImportStatus::Pending,
        ]);

        resolve(StartProductImport::class)->execute($import);

        expect($import->refresh()->total_products)->toBe(2);
    });

    it('marks the import as failed when the file cannot be parsed', function (): void {
        Bus::fake();

        Storage::fake('local');
        Storage::disk('local')->put('imports/products.csv', "foo,bar\n1,2\n");

        $import = ProductImport::query()->create([
            'source' => 'csv',
            'disk' => 'local',
            'file_path' => 'imports/products.csv',
            'status' => ImportStatus::Pending,
        ]);

        expect(fn () => resolve(StartProductImport::class)->execute($import))
            ->toThrow(Shopper\Core\Exceptions\ProductImportException::class);

        $import->refresh();

        expect($import->status)->toBe(ImportStatus::Failed)
            ->and($import->errors)->toHaveCount(1)
            ->and($import->finished_at)->not->toBeNull();

        Bus::assertNothingBatched();
    });
});
