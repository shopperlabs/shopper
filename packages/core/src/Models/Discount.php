<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read int $id
 * @property-read string $code
 * @property-read string $type
 * @property-read string|int $value
 * @property-read string $apply_to
 * @property-read string $eligibility
 * @property-read int $usage_limit
 * @property-read int $total_use
 * @property-read bool $usage_limit_per_user
 * @property-read bool $is_active
 * @property-read \Illuminate\Support\Carbon $start_at
 * @property-read \Illuminate\Support\Carbon|null $end_at
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
    ];

    public function getTable(): string
    {
        return shopper_table('discounts');
    }

    public function hasReachedLimit(): bool
    {
        if (null !== $this->usage_limit) {
            return $this->total_use === $this->usage_limit;
        }

        return false;
    }

    public function items(): HasMany
    {
        return $this->hasMany(DiscountDetail::class, 'discount_id');
    }
}
