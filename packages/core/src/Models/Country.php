<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Shopper\Core\Database\Factories\CountryFactory;
use Shopper\Core\Models\Contracts\Country as CountryContract;
use Shopper\Core\Models\Traits\HasZones;

/**
 * @property-read int $id
 * @property-read string $name
 * @property-read string $name_official
 * @property-read string $region
 * @property-read string $subregion
 * @property-read string $cca3
 * @property-read string $cca2
 * @property-read string $flag
 * @property-read string $translated_name
 * @property-read string $svg_flag
 * @property-read float $latitude
 * @property-read float $longitude
 * @property-read array<string, mixed> $phone_calling_code
 * @property-read array<string, mixed> $currencies
 */
class Country extends Model implements CountryContract
{
    /** @use HasFactory<CountryFactory> */
    use HasFactory;

    use HasZones;

    public $timestamps = false;

    protected $guarded = [];

    public function getTable(): string
    {
        return shopper_table('countries');
    }

    public function countryFlag(): string
    {
        return url(shopper()->prefix().'/images/flags/'.mb_strtolower($this->cca2).'.svg');
    }

    protected static function newFactory(): CountryFactory
    {
        return CountryFactory::new();
    }

    protected function casts(): array
    {
        return [
            'phone_calling_code' => 'array',
            'currencies' => 'array',
        ];
    }

    protected function translatedName(): Attribute
    {
        return Attribute::get(function (): string {
            static $cache = [];

            $locale = app()->getLocale();

            if (! isset($cache[$locale])) {
                $path = __DIR__.'/../../resources/lang/countries/'.$locale.'.json';
                $cache[$locale] = file_exists($path)
                    ? json_decode(file_get_contents($path), true)
                    : [];
            }

            return $cache[$locale][$this->cca2] ?? $this->attributes['name'];
        });
    }

    protected function svgFlag(): Attribute
    {
        return Attribute::get(
            fn (): string => $this->countryFlag()
        );
    }
}
