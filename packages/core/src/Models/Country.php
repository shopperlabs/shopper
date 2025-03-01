<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Shopper\Core\Database\Factories\CountryFactory;
use Shopper\Core\Traits\HasZones;

/**
 * @property-read int $id
 * @property string $name
 * @property string $name_official
 * @property string $region
 * @property string $subregion
 * @property string $cca3
 * @property string $cca2
 * @property string $flag
 * @property string $svg_flag
 * @property float $latitude
 * @property float $longitude
 * @property array $phone_calling_code
 * @property array $currencies
 */
class Country extends Model
{
    use HasFactory;
    use HasZones;

    public $timestamps = false;

    protected $guarded = [];

    protected $casts = [
        'phone_calling_code' => 'array',
        'currencies' => 'array',
    ];

    protected $appends = [
        'svg_flag',
    ];

    public function getTable(): string
    {
        return shopper_table('countries');
    }

    protected static function newFactory(): CountryFactory
    {
        return CountryFactory::new();
    }

    protected function svgFlag(): Attribute
    {
        return Attribute::get(
            fn (): string => url(shopper()->prefix() . '/images/flags/' . mb_strtolower($this->cca2) . '.svg')
        );
    }
}
