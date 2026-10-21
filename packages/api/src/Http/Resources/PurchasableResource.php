<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Resources;

use Illuminate\Database\Eloquent\Model;
use Shopper\Core\Models\Contracts\ProductVariant;

final class PurchasableResource
{
    public static function make(Model $purchasable): JsonApiResource
    {
        return $purchasable instanceof ProductVariant
            ? ProductVariantResource::make($purchasable)
            : ProductResource::make($purchasable);
    }
}
