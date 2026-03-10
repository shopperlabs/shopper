<?php

declare(strict_types=1);

namespace Shopper\Models;

use Shopper\Core\Models\ProductVariant as CoreProductVariant;
use Shopper\Models\Traits\HasMedia;
use Shopper\Models\Traits\RegistersMediaCollections;
use Spatie\MediaLibrary\HasMedia as SpatieHasMedia;

class ProductVariant extends CoreProductVariant implements SpatieHasMedia
{
    use HasMedia, RegistersMediaCollections {
        RegistersMediaCollections::registerMediaCollections insteadof HasMedia;
    }
}
