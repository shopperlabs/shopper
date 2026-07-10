<?php

declare(strict_types=1);

namespace Shopper\Core\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
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

    public function handle(): void
    {
        if (! $this->model instanceof HasMedia) {
            return;
        }

        $fileName = basename((string) parse_url($this->url, PHP_URL_PATH));

        if ($fileName !== '' && $this->model->media()->where('file_name', $fileName)->exists()) {
            return;
        }

        $media = $this->model->addMediaFromUrl($this->url); // @phpstan-ignore-line

        if ($this->name !== null) {
            $media->usingName($this->name);
        }

        $media->toMediaCollection((string) config('shopper.media.storage.collection_name', 'default'));
    }
}
