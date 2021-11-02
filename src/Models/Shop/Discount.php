<?php

namespace Shopper\Framework\Models\Shop;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    /**
     * Get the table associated with the model.
     */
    public function getTable(): string
    {
        return shopper_table('discounts');
    }

    /**
     * Determine if the discount code has reached his limit usage.
     */
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
