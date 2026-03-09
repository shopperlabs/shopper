<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Shopper\Core\Database\Factories\TaxZoneFactory;
use Shopper\Core\Models\Contracts\TaxZone as TaxZoneContract;
use Shopper\Core\Traits\HasModelContract;

/**
 * @property-read int $id
 * @property-read ?string $name
 * @property-read int $country_id
 * @property-read ?string $province_code
 * @property-read bool $is_tax_inclusive
 * @property-read ?int $parent_id
 * @property-read ?int $provider_id
 * @property-read array<string, mixed>|null $metadata
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 * @property-read string $display_name
 * @property-read Country $country
 * @property-read ?TaxZone $parent
 * @property-read EloquentCollection<int, TaxZone> $children
 * @property-read EloquentCollection<int, TaxRate> $rates
 * @property-read ?TaxProvider $provider
 */
class TaxZone extends Model implements TaxZoneContract
{
    /** @use HasFactory<TaxZoneFactory> */
    use HasFactory;

    use HasModelContract;

    protected $guarded = [];

    public static function configuredClass(): string
    {
        return config('shopper.models.tax_zone', static::class);
    }

    public function getTable(): string
    {
        return shopper_table('tax_zones');
    }

    /**
     * @return BelongsTo<Country, $this>
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    /**
     * @return BelongsTo<TaxZone, $this>
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(config('shopper.models.tax_zone'), 'parent_id');
    }

    /**
     * @return HasMany<TaxZone, $this>
     */
    public function children(): HasMany
    {
        return $this->hasMany(config('shopper.models.tax_zone'), 'parent_id');
    }

    /**
     * @return HasMany<TaxRate, $this>
     */
    public function rates(): HasMany
    {
        return $this->hasMany(config('shopper.models.tax_rate'), 'tax_zone_id');
    }

    /**
     * @return BelongsTo<TaxProvider, $this>
     */
    public function provider(): BelongsTo
    {
        return $this->belongsTo(TaxProvider::class, 'provider_id');
    }

    protected static function newFactory(): TaxZoneFactory
    {
        return TaxZoneFactory::new();
    }

    protected function displayName(): Attribute
    {
        return Attribute::get(function (): string {
            $countryName = $this->country->translated_name;

            if ($this->name) {
                return $countryName.' — '.$this->name;
            }

            return $countryName;
        });
    }

    protected function casts(): array
    {
        return [
            'is_tax_inclusive' => 'boolean',
            'metadata' => 'array',
        ];
    }
}
