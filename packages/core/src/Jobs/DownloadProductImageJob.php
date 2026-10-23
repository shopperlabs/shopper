<?php

declare(strict_types=1);

namespace Shopper\Core\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Client\RequestException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Jobs\SyncJob;
use Illuminate\Queue\Middleware\WithoutOverlapping;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Shopper\Core\Exceptions\ProductImportException;
use Shopper\Core\Import\ImageDownloader;
use Shopper\Core\Models\Contracts\ProductVariant;
use Shopper\Core\Models\ProductImport;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileUnacceptableForCollection;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Throwable;

final class DownloadProductImageJob implements ShouldQueue
{
    use Batchable;
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    private const int MAX_RECORDED_ERRORS = 100;

    public int $tries = 3;

    public bool $deleteWhenMissingModels = true;

    /** @var array<int, int> */
    public array $backoff = [10, 60];

    public function __construct(
        public Model $model,
        public string $url,
        public ?string $name = null,
        public ?int $position = null,
    ) {}

    /**
     * @return array<int, WithoutOverlapping>
     */
    public function middleware(): array
    {
        return [
            (new WithoutOverlapping($this->model->getMorphClass().':'.$this->model->getKey().':'.sha1($this->url)))
                ->dontRelease()
                ->expireAfter(60),
        ];
    }

    public function handle(ImageDownloader $downloader): void
    {
        if (! $this->model instanceof HasMedia) {
            return;
        }

        if ($this->model->media()->where('custom_properties->source_url', $this->url)->exists()) {
            return;
        }

        try {
            $this->addImage($this->model, $downloader);
        } catch (Throwable $e) {
            if ($this->willRetry($e) || ! $this->recordFailure($e)) {
                throw $e;
            }
        }
    }

    private function addImage(HasMedia $model, ImageDownloader $downloader): void
    {
        $fileName = basename(urldecode((string) parse_url($this->url, PHP_URL_PATH)));
        $path = $downloader->download($this->url);

        try {
            if (! str_contains($fileName, '.')) {
                $fileName = ($fileName ?: 'file').'.'.Str::after((string) mime_content_type($path), '/');
            }

            DB::transaction(fn (): Media => $model->addMedia($path)
                ->preservingOriginal()
                ->usingFileName($fileName)
                ->usingName($this->name ?? pathinfo($fileName, PATHINFO_FILENAME))
                ->withCustomProperties(['source_url' => $this->url])
                ->setOrder($this->position)
                ->toMediaCollection((string) config('shopper.media.storage.collection_name', 'default')));
        } finally {
            unlink($path);
        }
    }

    private function willRetry(Throwable $e): bool
    {
        if ($this->job instanceof SyncJob || $this->attempts() >= $this->tries) {
            return false;
        }

        return ! $e instanceof ProductImportException
            && ! $e instanceof FileUnacceptableForCollection
            && ! ($e instanceof RequestException && $e->response->clientError() && ! $e->response->tooManyRequests());
    }

    private function recordFailure(Throwable $e): bool
    {
        $importId = $this->batchId === null
            ? null
            : ProductImport::query()->where('batch_id', $this->batchId)->value('id');

        if ($importId === null) {
            return false;
        }

        $handle = $this->model instanceof ProductVariant
            ? $this->model->product()->value('slug')
            : $this->model->getAttribute('slug');

        DB::transaction(function () use ($importId, $e, $handle): void {
            /** @var ?ProductImport $import */
            $import = ProductImport::query()->lockForUpdate()->find($importId);

            if ($import === null || count($import->errors ?? []) >= self::MAX_RECORDED_ERRORS) {
                return;
            }

            $import->update([
                'errors' => [...($import->errors ?? []), [
                    'handle' => (string) $handle,
                    'message' => "Image [{$this->url}] could not be imported: {$e->getMessage()}",
                ]],
            ]);
        });

        return true;
    }
}
