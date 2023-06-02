<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Shopper\Framework\Models\System\Country;

class Inventory extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<string>|bool
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
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
