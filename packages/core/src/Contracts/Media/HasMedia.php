<?php

declare(strict_types=1);

namespace Shopper\Core\Contracts\Media;

use Shopper\Core\Media\MediaCollectionConfig;

interface HasMedia
{
    /** @return array<string, MediaCollectionConfig> */
    public function getMediaCollections(): array;
}
