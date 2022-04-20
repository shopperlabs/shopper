<?php

namespace Shopper\Framework\Traits;

use Shopper\Framework\Models\Shop\Product\Attribute;
use Shopper\Framework\Models\Shop\Product\ProductAttribute;

trait WithAttributes
{
    public function attributeModelValue(string $type): string
    {
        if (in_array($type, ['text', 'number', 'richtext', 'select', 'datepicker', 'radio'])) {
            return 'single';
        } else {
            return 'multiple';
        }
    }

    public function getAttributes(): \Illuminate\Database\Eloquent\Collection|array
    {
        return Attribute::query()
            ->whereNotIn('id', $this->productAttributes->pluck('attribute_id')->toArray())
            ->where('is_enabled', true)
            ->get();
    }

    public function getProductAttributes(): \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|array
    {
        return ProductAttribute::query()
            ->with('values')
            ->where('product_id', $this->product->id)
            ->get()
            ->map(function ($pa) {
                $pa['type'] = $pa->attribute->type;
                $pa['model'] = $this->attributeModelValue($pa->attribute->type);

                return $pa;
            });
    }
}
