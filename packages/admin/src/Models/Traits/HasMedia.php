<?php

declare(strict_types=1);

namespace Shopper\Models\Traits;

use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

trait HasMedia
{
    use InteractsWithMedia;

    public function getThumbnail(): ?Media
    {
        return $this->getFirstMedia((string) config('shopper.media.storage.thumbnail_collection', 'thumbnail'))
            ?? $this->getMedia((string) config('shopper.media.storage.collection_name', 'uploads'))->first();
    }

    public function getThumbnailUrl(string $conversionName = ''): string
    {
        $media = $this->getThumbnail();

        if (! $media instanceof Media) {
            return shopper_fallback_url();
        }

        return $conversionName !== ''
            ? $media->getAvailableUrl([$conversionName])
            : $media->getUrl();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $conversions = config('shopper.media.conversions', []);

        foreach ($conversions as $key => $conversion) {
            $this->addMediaConversion($key)
                ->fit(
                    Fit::Fill,
                    $conversion['width'],
                    $conversion['height']
                )->keepOriginalImageFormat();
        }
    }
}
