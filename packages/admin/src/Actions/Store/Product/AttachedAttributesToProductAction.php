<?php

declare(strict_types=1);

namespace Shopper\Actions\Store\Product;

use Illuminate\Support\Arr;
use Shopper\Core\Models\Product;

final class AttachedAttributesToProductAction
{
    public function __invoke(Product $product, array $attributes, array $customValues = []): void
    {
        foreach ($attributes as $attributeId => $values) {
            Arr::map(
                array: Arr::wrap($values),
                callback: fn ($value) => $product->options()
                    ->attach($attributeId, ['attribute_value_id' => (int) $value])
            );
        }

        if (count($customValues) > 0) {
            foreach ($customValues as $attributeId => $value) {
                $product->options()->attach([
                    $attributeId => ['attribute_custom_value' => $value],
                ]);
            }
        }
    }
}
