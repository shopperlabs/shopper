<?php

declare(strict_types=1);

namespace Shopper\Core\Traits\Attributes;

use Illuminate\Database\Eloquent\Collection;
use Shopper\Core\Models\Attribute;
use Shopper\Core\Models\ProductAttribute;

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

    public function getAttributes(): Collection
    {
        return Attribute::with('values')
            ->select(['id', 'name', 'type', 'is_enabled', 'icon', 'description'])
            ->get();
    }

    public function getProductAttributes(): \Illuminate\Support\Collection
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
