<?php

declare(strict_types=1);

namespace Shopper\Actions\Store\Product;

use Illuminate\Support\Facades\DB;
use Shopper\Core\Models\AttributeProduct;
use Shopper\Core\Models\Contracts\Product as ProductContract;
use Shopper\Core\Models\Contracts\ProductVariant as ProductVariantContract;

final class DetachAttributesToProductAction
{
    public function __invoke(AttributeProduct $attributeProduct, ProductContract $product): void
    {
        DB::transaction(function () use ($attributeProduct, $product): void {
            if ($product->variants()->count()) {
                $product->variants->each(
                    fn (ProductVariantContract $variant) => $variant->values()->detach($attributeProduct->attribute_value_id)
                );
            }

            $attributeProduct->delete();
        });
    }
}
