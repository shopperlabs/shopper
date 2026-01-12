<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Shopper\Core\Database\Factories\CurrencyFactory;
use Shopper\Core\Models\Contracts\Currency as CurrencyContract;

/**
 * @property-read int $id
 * @property-read string $name
 * @property-read string $code
 * @property-read string $symbol
 * @property-read string $format
 * @property-read float $exchange_rate
 * @property-read bool $is_enabled
 * @property-read ?Zone $zone
 */
class Currency extends Model implements CurrencyContract
{
    /** @use HasFactory<CurrencyFactory> */
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];

    public function getTable(): string
    {
        return shopper_table('currencies');
    }

    /**
     * @return HasOne<Zone, $this>
     */
    public function zone(): HasOne
    {
        return $this->hasOne(Zone::class);
    }

    protected static function booted(): void
    {
        static::addGlobalScope('enabled', function (Builder $query): void {
            $query->where('is_enabled', true);
        });
    }

    protected static function newFactory(): CurrencyFactory
    {
        return CurrencyFactory::new();
    }

    protected function casts(): array
    {
        return [
            'exchange_rate' => 'float',
            'is_enabled' => 'boolean',
        ];
    }
}
