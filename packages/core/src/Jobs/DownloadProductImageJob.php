<?php

declare(strict_types=1);

namespace Shopper\Core\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use Shopper\Core\Import\ImageDownloader;
use Spatie\MediaLibrary\HasMedia;

final class DownloadProductImageJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        public Model $model,
        public string $url,
        public ?string $name = null,
    ) {}

    public function handle(ImageDownloader $downloader): void
    {
        if (! $this->model instanceof HasMedia) {
            return;
        }

        $fileName = basename(urldecode((string) parse_url($this->url, PHP_URL_PATH)));

        if ($fileName !== '' && $this->model->media()->where('file_name', $fileName)->exists()) {
            return;
        }

        $path = $downloader->download($this->url);

        try {
            if (! str_contains($fileName, '.')) {
                $fileName = ($fileName ?: 'file').'.'.Str::after((string) mime_content_type($path), '/');
            }

            $this->model->addMedia($path)
                ->preservingOriginal()
                ->usingFileName($fileName)
                ->usingName($this->name ?? pathinfo($fileName, PATHINFO_FILENAME))
                ->toMediaCollection((string) config('shopper.media.storage.collection_name', 'default'));
        } finally {
            unlink($path);
        }
    }
}
