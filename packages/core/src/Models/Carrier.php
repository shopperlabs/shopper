<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Shopper\Core\Database\Factories\CarrierFactory;
use Shopper\Core\Models\Contracts\Carrier as CarrierContract;
use Shopper\Core\Models\Traits\HasSlug;
use Shopper\Core\Models\Traits\HasZones;

/**
 * @property-read int $id
 * @property-read string $name
 * @property-read bool $is_enabled
 * @property-read ?string $slug
 * @property-read ?string $logo
 * @property-read ?string $link_url
 * @property-read ?string $description
 * @property-read ?int $shipping_amount
 * @property-read array<string, mixed>|null $metadata
 * @property-read Collection<int, CarrierOption> $options
 */
class Carrier extends Model implements CarrierContract
{
    /** @use HasFactory<CarrierFactory> */
    use HasFactory;

    use HasSlug;
    use HasZones;

    protected $guarded = [];

    public function getTable(): string
    {
        return shopper_table('carriers');
    }

    /**
     * @param  Builder<Carrier>  $query
     * @return Builder<Carrier>
     */
    public function scopeEnabled(Builder $query): Builder
    {
        return $query->where('is_enabled', true);
    }

    /**
     * @return HasMany<CarrierOption, $this>
     */
    public function options(): HasMany
    {
        return $this->hasMany(CarrierOption::class);
    }

    protected static function newFactory(): CarrierFactory
    {
        return CarrierFactory::new();
    }

    protected function casts(): array
    {
        return [
            'is_enabled' => 'boolean',
            'metadata' => 'array',
        ];
    }
}
