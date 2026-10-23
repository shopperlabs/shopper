<?php

declare(strict_types=1);

namespace Shopper\Core\Import;

use Illuminate\Bus\Batch;
use Illuminate\Bus\PendingBatch;
use Illuminate\Queue\DeferredQueue;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\LazyCollection;
use Shopper\Core\Enum\ImportStatus;
use Shopper\Core\Events\Products\ProductImportCompleted;
use Shopper\Core\Import\Contracts\SupportsColumnMapping;
use Shopper\Core\Jobs\ImportProductsChunkJob;
use Shopper\Core\Models\ProductImport;
use Throwable;

final class StartProductImport
{
    public function __construct(
        private ImportManager $manager,
    ) {}

    public function execute(ProductImport $import): void
    {
        $chunkSize = (int) config('shopper.core.import.chunk_size', 25);

        try {
            $source = $this->manager->source($import->source);

            if ($source instanceof SupportsColumnMapping && $import->mapping) {
                $source = $source->withMapping($import->mapping);
            }

            $path = Storage::disk($import->disk)->path($import->file_path);
            $total = 0;

            $jobs = $source->read($path)
                ->chunk($chunkSize)
                ->map(function (LazyCollection $rows) use ($import, &$total): ImportProductsChunkJob {
                    $rows = $rows->values()->all();
                    $total += count($rows);

                    return new ImportProductsChunkJob($import->id, $rows);
                })
                ->all();
        } catch (Throwable $e) {
            $this->fail($import, $e);
        }

        if ($jobs === []) {
            $import->update([
                'status' => ImportStatus::Completed,
                'started_at' => now(),
                'finished_at' => now(),
            ]);

            event(new ProductImportCompleted($import));

            return;
        }

        $import->update([
            'status' => ImportStatus::Processing,
            'total_products' => $total,
            'started_at' => now(),
        ]);

        $importId = $import->id;

        $pending = Bus::batch($jobs)
            ->name("product-import-{$import->id}")
            ->allowFailures()
            ->before(function (Batch $batch) use ($importId): void {
                ProductImport::query()->whereKey($importId)->update(['batch_id' => $batch->id]);
            })
            ->finally(function (Batch $batch) use ($importId): void {
                /** @var ?ProductImport $import */
                $import = ProductImport::query()->find($importId);

                if ($import === null) {
                    return;
                }

                $import->update([
                    'status' => filled($import->errors) || $batch->failedJobs > 0
                        ? ImportStatus::CompletedWithErrors
                        : ImportStatus::Completed,
                    'finished_at' => now(),
                ]);

                event(new ProductImportCompleted($import));
            });

        if (Queue::connection() instanceof DeferredQueue) {
            defer(function () use ($pending, $import): void {
                $connection = Queue::getDefaultDriver();

                Queue::setDefaultDriver('sync');

                try {
                    $this->dispatch($pending, $import);
                } finally {
                    Queue::setDefaultDriver($connection);
                }
            }, always: true);

            return;
        }

        $this->dispatch($pending, $import);
    }

    private function dispatch(PendingBatch $pending, ProductImport $import): void
    {
        try {
            $pending->dispatch();
        } catch (Throwable $e) {
            $this->fail($import, $e);
        }
    }

    private function fail(ProductImport $import, Throwable $e): never
    {
        $import->update([
            'status' => ImportStatus::Failed,
            'errors' => [['handle' => '*', 'message' => $e->getMessage()]],
            'finished_at' => now(),
        ]);

        throw $e;
    }
}
