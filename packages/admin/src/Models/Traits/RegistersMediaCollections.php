<?php

declare(strict_types=1);

namespace Shopper\Models\Traits;

trait RegistersMediaCollections
{
    public function registerMediaCollections(): void
    {
        foreach ($this->getMediaCollections() as $config) {
            $collection = $this->addMediaCollection($config->name)
                ->useDisk($config->disk)
                ->useFallbackUrl($config->fallbackUrl ?? '');

            if ($config->singleFile) {
                $collection->singleFile();
            }

            if ($config->acceptsMimeTypes !== []) {
                $collection->acceptsMimeTypes($config->acceptsMimeTypes);
            }
        }
    }
}
