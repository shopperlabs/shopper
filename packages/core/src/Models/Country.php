<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int $id
 * @property string $name
 * @property string $name_official
 * @property string $cca3
 * @property string $cca2
 * @property string $flag
 * @property float $latitude
 * @property float $longitude
 * @property array $currencies
 */
class Country extends Model
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
