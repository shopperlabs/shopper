<?php

declare(strict_types=1);

use Illuminate\Bus\PendingBatch;
use Illuminate\Database\QueryException;
use Illuminate\Support\Defer\DeferredCallback;
use Illuminate\Support\Defer\DeferredCallbackCollection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Shopper\Core\Enum\ImportStatus;
use Shopper\Core\Events\Products\ProductImportCompleted;
use Shopper\Core\Import\StartProductImport;
use Shopper\Core\Models\Inventory;
use Shopper\Core\Models\ProductImport;

use function Illuminate\Events\queueable;

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

    it('completes an import whose file has no products', function (): void {
        Event::fake([ProductImportCompleted::class]);

        $import = pendingImport('header_only.csv');

        resolve(StartProductImport::class)->execute($import);

        expect($import->refresh()->status)->toBe(ImportStatus::Completed);

        Event::assertDispatched(ProductImportCompleted::class);
    });

    it('finishes an import and runs its queued listeners on an in-process queue', function (string $connection): void {
        config(['queue.default' => $connection]);
        setupCurrencies();
        Inventory::factory()->create(['is_default' => true]);
        Http::fake(fn () => Http::response('', 503));
        Event::listen(queueable(function (ProductImportCompleted $event): void {
            cache()->put('notified-import', $event->import->id);
        }));

        $import = pendingImport('dead_image.csv');

        resolve(StartProductImport::class)->execute($import);

        app(DeferredCallbackCollection::class)->invokeWhen(fn (DeferredCallback $callback): bool => $callback->always);

        $import->refresh();

        expect($import->status)->toBe(ImportStatus::CompletedWithErrors)
            ->and($import->imported_count)->toBe(2)
            ->and($import->failed_count)->toBe(0)
            ->and($import->errors)->toHaveCount(1)
            ->and($import->errors[0]['handle'])->toBe('tee')
            ->and(cache('notified-import'))->toBe($import->id);
    })->with(['sync', 'deferred']);

    it('marks the import as failed when its batch cannot be queued', function (): void {
        config(['queue.default' => 'database', 'queue.connections.database.table' => 'missing_jobs']);

        $import = pendingImport();

        expect(fn () => resolve(StartProductImport::class)->execute($import))->toThrow(QueryException::class)
            ->and($import->refresh()->status)->toBe(ImportStatus::Failed);
    });

    it('marks the import as failed when its source is not supported', function (): void {
        $import = ProductImport::query()->create([
            'source' => 'unknown',
            'disk' => 'local',
            'file_path' => 'imports/products.csv',
            'status' => ImportStatus::Pending,
        ]);

        expect(fn () => resolve(StartProductImport::class)->execute($import))->toThrow(InvalidArgumentException::class)
            ->and($import->refresh()->status)->toBe(ImportStatus::Failed);
    });

    it('includes a failed image in the errors of the completed import', function (): void {
        config(['queue.default' => 'database']);
        setupCurrencies();
        Inventory::factory()->create(['is_default' => true]);
        Http::fake(fn () => Http::response('', 404));
        Event::fake([ProductImportCompleted::class]);

        resolve(StartProductImport::class)->execute(pendingImport('dead_image.csv'));

        Artisan::call('queue:work', ['connection' => 'database', '--stop-when-empty' => true, '--memory' => PHP_INT_MAX]);

        Event::assertDispatched(fn (ProductImportCompleted $event): bool => $event->import->status === ImportStatus::CompletedWithErrors
            && $event->import->errors[0]['handle'] === 'tee');
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
