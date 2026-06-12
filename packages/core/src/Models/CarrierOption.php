<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Shopper\Core\Database\Factories\CarrierOptionFactory;
use Shopper\Core\Models\Contracts\CarrierOption as CarrierOptionContract;
use Shopper\Core\Models\Traits\HasPublicId;

/**
 * @property-read int $id
 * @property-read ?string $public_id
 * @property-read string $name
 * @property-read ?string $description
 * @property-read int $price
 * @property-read int $zone_id
 * @property-read int $carrier_id
 * @property-read bool $is_enabled
 * @property-read array<string, mixed>|null $metadata
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 * @property-read Zone $zone
 * @property-read Carrier $carrier
 */
class CarrierOption extends Model implements CarrierOptionContract
{
    /** @use HasFactory<CarrierOptionFactory> */
    use HasFactory;

    use HasPublicId;

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

    /**
     * @param  Builder<CarrierOption>  $query
     * @return Builder<CarrierOption>
     */
    #[Scope]
    protected function enabled(Builder $query): Builder
    {
        return $query->where('is_enabled', true);
    }

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'is_enabled' => 'boolean',
        ];
    }
}
