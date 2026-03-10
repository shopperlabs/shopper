<?php

declare(strict_types=1);

namespace Shopper\Core\Models\Traits;

use Shopper\Core\Media\MediaCollectionConfig;

trait HasMediaCollections
{
    /** @return array<string, MediaCollectionConfig> */
    public function getMediaCollections(): array
    {
        return $this->defaultMediaCollections();
    }

    /** @return array<string, MediaCollectionConfig> */
    protected function defaultMediaCollections(): array
    {
        $default = config('shopper.media.storage.collection_name', 'default');
        $thumbnail = config('shopper.media.storage.thumbnail_collection', 'thumbnail');
        $disk = config('shopper.media.storage.disk_name', 'public');
        $mimeTypes = config('shopper.media.accepts_mime_types', []);
        $fallback = shopper_fallback_url();

        return [
            $default => new MediaCollectionConfig(
                name: $default,
                disk: $disk,
                acceptsMimeTypes: $mimeTypes,
                fallbackUrl: $fallback,
            ),
            $thumbnail => new MediaCollectionConfig(
                name: $thumbnail,
                disk: $disk,
                singleFile: true,
                acceptsMimeTypes: $mimeTypes,
                fallbackUrl: $fallback,
            ),
        ];
    }
}
