<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read int $id
 * @property string $name
 * @property int $price
 * @property int $zone_id
 * @property int $carrier_id
 * @property bool $is_enabled
 * @property \Shopper\Core\Models\Zone $zone
 * @property \Shopper\Core\Models\Carrier $carrier
 * @property array $metadata
 */
class CarrierOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'is_enabled',
        'price',
        'carrier_id',
        'zone_id',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_enabled' => 'boolean',
    ];

    protected function price(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100,
        );
    }

    public function getTable(): string
    {
        return shopper_table('carrier_options');
    }

    public function isEnabled(): bool
    {
        return $this->is_enabled;
    }

    public function scopeEnabled(Builder $query): Builder
    {
        return $query->where('is_enabled', true);
    }

    public function carrier(): BelongsTo
    {
        return $this->belongsTo(Carrier::class, 'carrier_id');
    }

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class, 'zone_id');
    }
}
