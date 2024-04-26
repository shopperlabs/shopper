<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property-read int $id
 * @property string $name
 * @property string $code
 * @property string $symbol
 * @property string $format
 * @property float $exchange_rate
 */
class Currency extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'name',
        'code',
        'symbol',
        'format',
        'exchange_rate',
    ];

    public function getTable(): string
    {
        return shopper_table('currencies');
    }

    public function zone(): HasOne
    {
        return $this->hasOne(Zone::class);
    }
}
