<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Model;

final class Currency extends Model
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
        return shopper_table('system_currencies');
    }
}
