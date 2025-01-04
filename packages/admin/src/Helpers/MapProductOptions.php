<?php

declare(strict_types=1);

namespace Shopper\Helpers;

use Illuminate\Support\Arr;
use Shopper\Core\Models\Attribute;
use Shopper\Core\Models\AttributeProduct;
use Shopper\Core\Models\AttributeValue;
use Shopper\Core\Models\Product;

final class MapProductOptions
{
    public static function generate(Product $product): array
    {
        $values = AttributeProduct::with(['attribute', 'value'])
            ->where('product_id', $product->id)
            ->get()
            ->map(fn ($attributeProduct) => $attributeProduct->value)
            ->filter(fn ($value) => $value instanceof AttributeValue);

        $options = collect();

        foreach ($product->options as $option) {
            if ($option->hasTextValue()) {
                continue;
            }

            $attributeValues = $values->where('attribute_id', $option->id)
                ->map(fn ($attributeValue) => self::mapOptionValue($attributeValue))
                ->toArray();

            $options->push(self::mapOption($option, $attributeValues));
        }

        return $options->groupBy('id')
            ->map(fn ($group, $key) => Arr::collapse($group))
            ->values()
            ->toArray();
    }

    protected static function mapOption(Attribute $attribut, array $values = []): array
    {
        return [
            'id' => $attribut->id,
            'key' => 'attribute_' . $attribut->id,
            'name' => $attribut->name,
            'values' => $values,
        ];
    }

    protected static function mapOptionValue(AttributeValue $attributeValue): array
    {
        return [
            'id' => $attributeValue->id,
            'key' => 'value_' . $attributeValue->id,
            'value' => $attributeValue->value,
        ];
    }
}
