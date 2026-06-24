<?php

declare(strict_types=1);

namespace Shopper\Models;

use Shopper\Core\Models\AttributeProduct as CoreAttributeProduct;
use Shopper\Models\Traits\HasMedia;
use Shopper\Models\Traits\RegistersMediaCollections;
use Spatie\MediaLibrary\HasMedia as SpatieHasMedia;

class AttributeProduct extends CoreAttributeProduct implements SpatieHasMedia
{
    use HasMedia, RegistersMediaCollections {
        RegistersMediaCollections::registerMediaCollections insteadof HasMedia;
    }
}
