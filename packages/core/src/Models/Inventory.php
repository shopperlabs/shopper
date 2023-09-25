<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read int $id
 * @property int $country_id
 * @property string $name
 * @property string $email
 * @property string $city
 * @property string|null $description
 * @property string|null $street_address
 * @property string|null $street_address_plus
 * @property string|null $zipcode
 * @property string|null $phone_number
 * @property bool $is_default
 */
final class Inventory extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    protected static function boot(): void
    {
        parent::boot();

        self::creating(function ($inventory): void {
            if ($inventory->is_default) {
                static::query()->update(['is_default' => false]);
            }
        });

        self::updating(function ($inventory): void {
            if ($inventory->is_default) {
                static::query()->update(['is_default' => false]);
            }
        });
    }

    public function getTable(): string
    {
        return shopper_table('inventories');
    }

    public function scopeDefault(Builder $query): Builder
    {
        return $query->where('is_default', true);
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
