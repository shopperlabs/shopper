<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Resources;

use Illuminate\Http\Request;
use Shopper\Core\Models\PaymentMethod;

/**
 * @mixin PaymentMethod
 */
class PaymentMethodResource extends JsonApiResource
{
    final public function toType(Request $request): string
    {
        return 'payment-methods';
    }

    public function toAttributes(Request $request): array
    {
        return [
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'instructions' => $this->instructions,
            'driver' => $this->driver,
            'logo' => $this->logo(),
        ];
    }
}
