<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Shopper\Core\Database\Factories\CurrencyFactory;

/**
 * @property-read int $id
 * @property string $name
 * @property string $code
 * @property string $symbol
 * @property string $format
 * @property float $exchange_rate
 * @property bool $is_enabled
 */
class Currency extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
        'exchange_rate' => 'float',
        'is_enabled' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('enabled', function (Builder $query): void {
            $query->where('is_enabled', true);
        });
    }

    public function getTable(): string
    {
        return shopper_table('currencies');
    }

    protected static function newFactory(): CurrencyFactory
    {
        return CurrencyFactory::new();
    }

    public function zone(): HasOne
    {
        return $this->hasOne(Zone::class);
    }
}
