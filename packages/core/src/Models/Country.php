<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

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

    public $timestamps = false;

    protected $fillable = [
        'name',
        'name_official',
        'region',
        'subregion',
        'cca3',
        'cca2',
        'flag',
        'latitude',
        'longitude',
        'phone_calling_code',
        'currencies',
    ];

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

    public function svgFlag(): Attribute
    {
        return Attribute::get(
            fn () => url(shopper()->prefix() . '/images/flags/' . mb_strtolower($this->cca2) . '.svg')
        );
    }

    public function zone(): MorphOne
    {
        return $this->morphOne(ZoneRelation::class, 'zonable');
    }
}
