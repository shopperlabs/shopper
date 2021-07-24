<?php

namespace Shopper\Framework\Models\System;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'code',
        'symbol',
        'format',
        'exchange_rate',
    ];

    /**
     * Get the table associated with the model.
     */
    public function getTable(): string
    {
        return shopper_table('system_currencies');
    }
}
