<?php

declare(strict_types=1);

namespace Shopper\Models;

use Shopper\Core\Models\Collection as CoreCollection;
use Shopper\Models\Traits\HasMedia;
use Shopper\Models\Traits\RegistersMediaCollections;
use Spatie\MediaLibrary\HasMedia as SpatieHasMedia;

class Collection extends CoreCollection implements SpatieHasMedia
{
    use HasMedia, RegistersMediaCollections {
        RegistersMediaCollections::registerMediaCollections insteadof HasMedia;
    }
}
