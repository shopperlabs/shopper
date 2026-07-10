<?php

declare(strict_types=1);

namespace Shopper\Core\Import;

use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
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
        $source = $this->manager->source($import->source);

        if ($source instanceof SupportsColumnMapping && $import->mapping) {
            $source = $source->withMapping($import->mapping);
        }

        $path = Storage::disk($import->disk)->path($import->file_path);
        $chunkSize = (int) config('shopper.core.import.chunk_size', 25);

        try {
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
            $import->update([
                'status' => ImportStatus::Failed,
                'errors' => [['handle' => '*', 'message' => $e->getMessage()]],
                'finished_at' => now(),
            ]);

            throw $e;
        }

        $import->update([
            'status' => ImportStatus::Processing,
            'total_products' => $total,
            'started_at' => now(),
        ]);

        $importId = $import->id;

        $batch = Bus::batch($jobs)
            ->name("product-import-{$import->id}")
            ->allowFailures()
            ->finally(function (Batch $batch) use ($importId): void {
                /** @var ?ProductImport $import */
                $import = ProductImport::query()->find($importId);

                if ($import === null) {
                    return;
                }

                $import->update([
                    'status' => $import->failed_count > 0 || $batch->failedJobs > 0
                        ? ImportStatus::CompletedWithErrors
                        : ImportStatus::Completed,
                    'finished_at' => now(),
                ]);

                event(new ProductImportCompleted($import));
            })
            ->dispatch();

        $import->update(['batch_id' => $batch->id]);
    }
}
