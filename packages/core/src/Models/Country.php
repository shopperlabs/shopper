<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Model;

final class Country extends Model
{
    public $timestamps = false;

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

    protected $casts = [
        'currencies' => 'array',
    ];

    public function getTable(): string
    {
        return shopper_table('system_countries');
    }
}
