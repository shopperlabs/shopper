<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Resources;

use Illuminate\Http\Request;
use Shopper\Core\Models\Currency;

/**
 * @mixin Currency
 */
final class CurrencyResource extends JsonApiResource
{
    public function toType(Request $request): string
    {
        return 'currencies';
    }

    public function toAttributes(Request $request): array
    {
        return [
            'name' => $this->name,
            'code' => $this->code,
            'symbol' => $this->symbol,
            'format' => $this->format,
            'exchange_rate' => $this->exchange_rate,
        ];
    }
}
