<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Shopper\Core\Database\Factories\CarrierOptionFactory;

/**
 * @property-read int $id
 * @property-read string $name
 * @property-read int $price
 * @property-read int $zone_id
 * @property-read int $carrier_id
 * @property-read bool $is_enabled
 * @property-read Zone $zone
 * @property-read Carrier $carrier
 * @property-read array<string, mixed>|null $metadata
 */
class CarrierOption extends Model
{
    /** @use HasFactory<CarrierOptionFactory> */
    use HasFactory;

    protected $guarded = [];

    public function getTable(): string
    {
        return shopper_table('carrier_options');
    }

    public function isEnabled(): bool
    {
        return $this->is_enabled;
    }

    /**
     * @param  Builder<CarrierOption>  $query
     * @return Builder<CarrierOption>
     */
    public function scopeEnabled(Builder $query): Builder
    {
        return $query->where('is_enabled', true);
    }

    /**
     * @return BelongsTo<Carrier, $this>
     */
    public function carrier(): BelongsTo
    {
        return $this->belongsTo(Carrier::class, 'carrier_id');
    }

    /**
     * @return BelongsTo<Zone, $this>
     */
    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class, 'zone_id');
    }

    protected static function newFactory(): CarrierOptionFactory
    {
        return CarrierOptionFactory::new();
    }

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'is_enabled' => 'boolean',
        ];
    }

    protected function price(): Attribute
    {
        return Attribute::make(
            get: fn (int $value): int => $value / 100,
            set: fn (int $value): int => $value * 100,
        );
    }
}
