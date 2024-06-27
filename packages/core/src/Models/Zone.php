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
use Shopper\Core\Traits\HasSlug;

/**
 * @property-read int $id
 * @property string $name
 * @property string | null $slug
 * @property string | null $code
 * @property bool $is_enabled
 * @property int | null $currency_id
 * @property array $metadata
 * @property string $carriers_name
 * @property string $countries_name
 * @property string $payments_name
 * @property \Shopper\Core\Models\Currency $currency
 * @property \Illuminate\Database\Eloquent\Collection $carriers
 * @property \Illuminate\Database\Eloquent\Collection $shippingOptions
 */
class Zone extends Model
{
    use HasFactory;
    use HasSlug;

    protected $fillable = [
        'name',
        'slug',
        'code',
        'is_enabled',
        'currency_id',
        'metadata',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'metadata' => 'array',
    ];

    public function getTable(): string
    {
        return shopper_table('zones');
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

    public function scopeEnabled(Builder $query): Builder
    {
        return $query->where('is_enabled', true);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function countries(): MorphToMany
    {
        return $this->morphedByMany(Country::class, 'zonable', shopper_table('zone_has_relations'));
    }

    public function paymentMethods(): MorphToMany
    {
        return $this->morphedByMany(PaymentMethod::class, 'zonable', shopper_table('zone_has_relations'));
    }

    public function carriers(): MorphToMany
    {
        return $this->morphedByMany(Carrier::class, 'zonable', shopper_table('zone_has_relations'));
    }

    public function shippingOptions(): HasMany
    {
        return $this->hasMany(CarrierOption::class);
    }
}
