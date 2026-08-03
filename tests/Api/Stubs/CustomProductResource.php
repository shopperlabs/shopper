<?php

declare(strict_types=1);

namespace Tests\Api\Stubs;

use Illuminate\Http\Request;
use Shopper\Api\Http\Resources\ProductResource;

class CustomProductResource extends ProductResource
{
    public function toAttributes(Request $request): array
    {
        return [
            ...parent::toAttributes($request),
            'warehouse_code' => 'WH-'.$this->resource->getKey(),
        ];
    }
}
