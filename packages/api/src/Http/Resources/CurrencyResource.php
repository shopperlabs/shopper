<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Resources;

use Illuminate\Http\Request;
use Shopper\Core\Models\Currency;
use Spatie\QueryBuilder\AllowedFilter;

/**
 * @mixin Currency
 */
class CurrencyResource extends JsonApiResource
{
    public static function filters(): array
    {
        return [
            AllowedFilter::partial('name'),
            AllowedFilter::exact('code'),
        ];
    }

    public static function sorts(): array
    {
        return ['name', 'code'];
    }

    final public function toType(Request $request): string
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
