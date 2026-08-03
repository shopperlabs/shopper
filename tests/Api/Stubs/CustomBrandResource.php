<?php

declare(strict_types=1);

namespace Tests\Api\Stubs;

use Illuminate\Http\Request;
use Shopper\Api\Http\Resources\BrandResource;

class CustomBrandResource extends BrandResource
{
    public function toAttributes(Request $request): array
    {
        return [
            ...parent::toAttributes($request),
            'brand_flag' => 'custom',
        ];
    }
}
