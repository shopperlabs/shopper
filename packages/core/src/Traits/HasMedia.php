<?php

declare(strict_types=1);

namespace Shopper\Core\Traits;

use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

trait HasMedia
{
    use InteractsWithMedia;

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(config('shopper.core.storage.collection_name'))
            ->useDisk(config('shopper.core.storage.disk_name'))
            ->acceptsMimeTypes(config('shopper.media.accepts_mime_types'))
            ->useFallbackUrl(shopper_fallback_url());

        $this->addMediaCollection(config('shopper.core.storage.thumbnail_collection'))
            ->singleFile()
            ->useDisk(config('shopper.core.storage.disk_name'))
            ->acceptsMimeTypes(config('shopper.media.accepts_mime_types'))
            ->useFallbackUrl(shopper_fallback_url());
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $conversions = config('shopper.media.conversions', []);

        foreach ($conversions as $key => $conversion) {
            $this->addMediaConversion($key)
                ->fit(
                    Manipulations::FIT_FILL,
                    $conversion['width'],
                    $conversion['height']
                )->keepOriginalImageFormat();
        }

        $this->addMediaConversion('thumb_200')
            ->fit(Manipulations::FIT_CROP, 200, 200)
            ->keepOriginalImageFormat();
    }
}
