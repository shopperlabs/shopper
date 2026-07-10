<?php

declare(strict_types=1);

namespace Shopper\Core\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Shopper\Core\Import\ProductRow;
use Shopper\Core\Import\ProductRowImporter;
use Shopper\Core\Models\ProductImport;
use Throwable;

final class ImportProductsChunkJob implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @param  array<int, ProductRow>  $rows
     */
    public function __construct(
        public int $importId,
        public array $rows,
    ) {}

    public function handle(ProductRowImporter $importer): void
    {
        if ($this->batch()?->cancelled()) {
            return;
        }

        $imported = 0;
        $errors = [];

        foreach ($this->rows as $row) {
            try {
                $importer->import($row);
                $imported++;
            } catch (Throwable $e) {
                $errors[] = ['handle' => $row->handle, 'message' => $e->getMessage()];
            }
        }

        if ($imported > 0) {
            ProductImport::query()->whereKey($this->importId)->increment('imported_count', $imported);
        }

        if ($errors !== []) {
            DB::transaction(function () use ($errors): void {
                /** @var ?ProductImport $import */
                $import = ProductImport::query()->lockForUpdate()->find($this->importId);

                $import?->update([
                    'failed_count' => $import->failed_count + count($errors),
                    'errors' => [...($import->errors ?? []), ...$errors],
                ]);
            });
        }
    }
}
