<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Shopper\Core\Observers\InventoryObserver;

/**
 * @property-read int $id
 * @property int $country_id
 * @property string $name
 * @property string $code
 * @property string $email
 * @property string $city
 * @property string|null $description
 * @property string|null $street_address
 * @property string|null $street_address_plus
 * @property string $postal_code
 * @property string|null $phone_number
 * @property bool $is_default
 */
class Inventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'email',
        'street_address',
        'street_address_plus',
        'postal_code',
        'city',
        'phone_number',
        'priority',
        'latitude',
        'longitude',
        'is_default',
        'country_id',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function getTable(): string
    {
        return shopper_table('inventories');
    }

    protected static function boot(): void
    {
        parent::boot();

        self::observe(InventoryObserver::class);
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
