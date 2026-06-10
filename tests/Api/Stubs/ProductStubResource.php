<?php

declare(strict_types=1);

namespace Tests\Api\Stubs;

use Illuminate\Http\Request;
use Shopper\Api\Http\Resources\JsonApiResource;

class ProductStubResource extends JsonApiResource
{
    /** @var array<int, string> */
    public array $attributes = ['name', 'slug'];

    public function toType(Request $request): string
    {
        return 'products';
    }
}
