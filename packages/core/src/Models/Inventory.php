<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Shopper\Core\Database\Factories\InventoryFactory;
use Shopper\Core\Observers\InventoryObserver;

/**
 * @property-read int $id
 * @property-read int $country_id
 * @property-read string $name
 * @property-read string $code
 * @property-read string $email
 * @property-read string $city
 * @property-read string|null $description
 * @property-read string|null $street_address
 * @property-read string|null $street_address_plus
 * @property-read string $postal_code
 * @property-read string|null $phone_number
 * @property-read bool $is_default
 */
#[ObservedBy(InventoryObserver::class)]
class Inventory extends Model
{
    /** @use HasFactory<InventoryFactory> */
    use HasFactory;

    protected $guarded = [];

    public function getTable(): string
    {
        return shopper_table('inventories');
    }

    /**
     * @param  Builder<Inventory>  $query
     * @return Builder<Inventory>
     */
    public function scopeDefault(Builder $query): Builder
    {
        return $query->where('is_default', true);
    }

    /**
     * @return BelongsTo<Country, $this>
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    /**
     * @return HasMany<InventoryHistory, $this>
     */
    public function histories(): HasMany
    {
        return $this->hasMany(InventoryHistory::class);
    }

    protected static function newFactory(): InventoryFactory
    {
        return InventoryFactory::new();
    }

    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
        ];
    }
}
