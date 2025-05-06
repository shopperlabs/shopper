<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Shopper\Core\Database\Factories\ZoneFactory;
use Shopper\Core\Traits\HasSlug;

/**
 * @property-read int $id
 * @property string $name
 * @property string $slug
 * @property string|null $code
 * @property bool $is_enabled
 * @property int|null $currency_id
 * @property array<array-key, mixed>|null $metadata
 * @property string $carriers_name
 * @property string $countries_name
 * @property string $payments_name
 * @property string $currency_code
 * @property-read \Shopper\Core\Models\Currency $currency
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Carrier> $carriers
 * @property-read \Illuminate\Database\Eloquent\Collection<int, CarrierOption> $shippingOptions
 * @property-read \Illuminate\Database\Eloquent\Collection<int, PaymentMethod> $paymentMethods
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Country> $countries
 */
class Zone extends Model
{
    /** @use HasFactory<ZoneFactory> */
    use HasFactory;

    use HasSlug;

    protected $guarded = [];

    public function getTable(): string
    {
        return shopper_table('zones');
    }

    protected function casts(): array
    {
        return [
            'is_enabled' => 'boolean',
            'metadata' => 'array',
        ];
    }

    protected static function newFactory(): ZoneFactory
    {
        return ZoneFactory::new();
    }

    public function isEnabled(): bool
    {
        return $this->is_enabled;
    }

    public function countriesName(): Attribute
    {
        $countries = $this->countries->pluck('name')->toArray();

        return Attribute::make(
            get: fn () => count($countries)
                ? implode(', ', array_map(fn ($item) => ucwords($item), $countries))
                : 'N/A'
        );
    }

    public function carriersName(): Attribute
    {
        $carriers = $this->carriers->pluck('name')->toArray();

        return Attribute::make(
            get: fn () => count($carriers)
                ? implode(', ', array_map(fn ($item) => ucwords($item), $carriers))
                : 'N/A'
        );
    }

    public function paymentsName(): Attribute
    {
        $paymentsMethods = $this->paymentMethods->pluck('title')->toArray();

        return Attribute::make(
            get: fn () => count($paymentsMethods)
                ? implode(', ', array_map(fn ($item) => ucwords($item), $paymentsMethods))
                : 'N/A'
        );
    }

    public function currencyCode(): Attribute
    {
        return Attribute::get(fn () => $this->loadMissing('currency')->currency->code);
    }

    /**
     * @param  Builder<Zone>  $query
     * @return Builder<Zone>
     */
    public function scopeEnabled(Builder $query): Builder
    {
        return $query->where('is_enabled', true);
    }

    /**
     * @return BelongsTo<Currency, $this>
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    /**
     * @return MorphToMany<Country, $this>
     */
    public function countries(): MorphToMany
    {
        return $this->morphedByMany(Country::class, 'zonable', shopper_table('zone_has_relations'));
    }

    /**
     * @return MorphToMany<PaymentMethod, $this>
     */
    public function paymentMethods(): MorphToMany
    {
        return $this->morphedByMany(PaymentMethod::class, 'zonable', shopper_table('zone_has_relations'));
    }

    /**
     * @return MorphToMany<Carrier, $this>
     */
    public function carriers(): MorphToMany
    {
        return $this->morphedByMany(Carrier::class, 'zonable', shopper_table('zone_has_relations'));
    }

    /**
     * @return HasMany<CarrierOption, $this>
     */
    public function shippingOptions(): HasMany
    {
        return $this->hasMany(CarrierOption::class);
    }
}
