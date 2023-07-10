<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read int $id
 * @property-read int $country_id
 * @property-read string $name
 * @property-read string $email
 * @property-read string $city
 * @property-read string|null $description
 * @property-read string|null $street_address
 * @property-read string|null $street_address_plus
 * @property-read string|null $zipcode
 * @property-read string|null $phone_number
 * @property-read bool $is_default
 */
class Inventory extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($inventory) {
            if ($inventory->is_default) {
                static::query()->update(['is_default' => false]);
            }
        });

        static::updating(function ($inventory) {
            if ($inventory->is_default) {
                static::query()->update(['is_default' => false]);
            }
        });
    }

    public function getTable(): string
    {
        return shopper_table('inventories');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function histories(): HasMany
    {
        return $this->hasMany(InventoryHistory::class);
    }
}
