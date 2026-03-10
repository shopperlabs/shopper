<?php

declare(strict_types=1);

namespace Shopper\Models\Traits;

use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

trait HasMedia
{
    use InteractsWithMedia;

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
