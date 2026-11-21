<?php

declare(strict_types=1);

namespace Tests\Api\Stubs;

use Illuminate\Http\Request;
use Shopper\Api\Http\Resources\ProductResource;
use Spatie\QueryBuilder\AllowedFilter;

class CustomProductResource extends ProductResource
{
    public static function filters(): array
    {
        return [...parent::filters(), AllowedFilter::exact('barcode')];
    }

    public static function includes(): array
    {
        return [...parent::includes(), 'supplier'];
    }

    public function toAttributes(Request $request): array
    {
        return [
            ...parent::toAttributes($request),
            'warehouse_code' => 'WH-'.$this->resource->getKey(),
            'brand_name' => $this->resource->brand?->name,
        ];
    }
}
