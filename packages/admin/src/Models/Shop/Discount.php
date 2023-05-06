<?php

declare(strict_types=1);

namespace Shopper\Framework\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $code
 * @property string $type
 * @property int|null $value
 * @property string $apply_to
 * @property string $eligibility
 * @property int $min_required
 * @property string $min_required_value
 * @property int|null $usage_limit
 * @property bool $usage_limit_per_user
 * @property bool $is_active
 * @property \Carbon\Carbon $start_at
 * @property \Carbon\Carbon $end_at
 */
class Discount extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'is_active',
        'code',
        'type',
        'value',
        'apply_to',
        'min_required',
        'min_required_value',
        'eligibility',
        'usage_limit',
        'usage_limit_per_user',
        'total_use',
        'start_at',
        'end_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
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
