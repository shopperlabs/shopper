<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Shopper\Core\Database\Factories\DiscountFactory;
use Shopper\Core\Enum\DiscountType;
use Shopper\Core\Models\Contracts\Discount as DiscountContract;

/**
 * @property-read int $id
 * @property-read string $code
 * @property-read DiscountType $type
 * @property-read int $value
 * @property-read string $apply_to
 * @property-read string $min_required
 * @property-read ?string $min_required_value
 * @property-read string $eligibility
 * @property-read int $total_use
 * @property-read ?int $usage_limit
 * @property-read bool $usage_limit_per_user
 * @property-read bool $is_active
 * @property-read ?int $zone_id
 * @property-read array<string, mixed>|null $metadata
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 * @property-read CarbonInterface $start_at
 * @property-read ?CarbonInterface $end_at
 * @property-read ?Zone $zone
 * @property-read Collection<array-key, DiscountDetail> $items
 */
class Discount extends Model implements DiscountContract
{
    /** @use HasFactory<DiscountFactory> */
    use HasFactory;

    protected $guarded = [];

    public function getTable(): string
    {
        return shopper_table('discounts');
    }

    public function hasReachedLimit(): bool
    {
        if ($this->usage_limit !== null) {
            return $this->total_use >= $this->usage_limit;
        }

        return false;
    }

    /**
     * @return HasMany<DiscountDetail, $this>
     */
    public function items(): HasMany
    {
        return $this->hasMany(DiscountDetail::class, 'discount_id');
    }

    /**
     * @return BelongsTo<Zone, $this>
     */
    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class, 'zone_id');
    }

    protected static function newFactory(): DiscountFactory
    {
        return DiscountFactory::new();
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'usage_limit_per_user' => 'boolean',
            'start_at' => 'datetime',
            'end_at' => 'datetime',
            'metadata' => 'array',
            'type' => DiscountType::class,
        ];
    }
}
