<?php

namespace Shopper\Framework\Models\System;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'name_official',
        'cca3',
        'cca2',
        'flag',
        'latitude',
        'longitude',
        'currencies',
    ];
}
