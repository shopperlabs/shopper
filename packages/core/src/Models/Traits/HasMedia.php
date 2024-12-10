<?php

declare(strict_types=1);

namespace Shopper\Core\Models\Traits;

use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

trait HasMedia
{
    use InteractsWithMedia;

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(config('shopper.media.storage.collection_name'))
            ->useDisk(config('shopper.media.storage.disk_name'))
            ->acceptsMimeTypes(config('shopper.media.accepts_mime_types'))
            ->useFallbackUrl(shopper_fallback_url());

        $this->addMediaCollection(config('shopper.media.storage.thumbnail_collection'))
            ->singleFile()
            ->useDisk(config('shopper.media.storage.disk_name'))
            ->acceptsMimeTypes(config('shopper.media.accepts_mime_types'))
            ->useFallbackUrl(shopper_fallback_url());
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
