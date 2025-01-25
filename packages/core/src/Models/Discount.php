<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Shopper\Core\Database\Factories\DiscountFactory;
use Shopper\Core\Enum\DiscountType;

/**
 * @property-read int $id
 * @property string $code
 * @property DiscountType $type
 * @property int $value
 * @property string $apply_to
 * @property string $eligibility
 * @property int $usage_limit
 * @property int $total_use
 * @property int | null $zone_id
 * @property bool $usage_limit_per_user
 * @property bool $is_active
 * @property array $metadata
 * @property \Illuminate\Support\Carbon $start_at
 * @property \Illuminate\Support\Carbon|null $end_at
 * @property-read Zone $zone
 * @property-read \Illuminate\Database\Eloquent\Collection | DiscountDetail[] $items
 */
class Discount extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
        'usage_limit_per_user' => 'boolean',
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'metadata' => 'array',
        'type' => DiscountType::class,
    ];

    public function getTable(): string
    {
        return shopper_table('discounts');
    }

    protected static function newFactory(): DiscountFactory
    {
        return DiscountFactory::new();
    }

    protected function value(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->type === DiscountType::FixedAmount
                ? $value / 100
                : $value,
            set: fn ($value) => $this->type === DiscountType::FixedAmount
                ? $value * 100
                : $value,
        );
    }

    public function hasReachedLimit(): bool
    {
        if ($this->usage_limit !== null) {
            return $this->total_use === $this->usage_limit;
        }

        return false;
    }

    public function items(): HasMany
    {
        return $this->hasMany(DiscountDetail::class, 'discount_id');
    }

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class, 'zone_id');
    }
}
