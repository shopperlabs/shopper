<?php

namespace Shopper\Framework\Models;

use Illuminate\Database\Eloquent\Model;
use Shopper\Framework\Models\Shop\Shop;

class Discount extends Model
{
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
        'date_start',
        'date_end',
        'shop_id',
    ];

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
        return shopper_table('discounts');
    }

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'date_start',
        'date_end',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
        'usage_limit_per_user' => 'boolean',
    ];

    /**
     * Determine if the discount code has reached his limit usage.
     *
     * @return bool
     */
    public function hasReachedLimit()
    {
        if (!is_null($this->usage_limit)) {
            return $this->total_use === $this->usage_limit;
        }

        return false;
    }

    /**
     * Get all associate model that's used this discount.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(DiscountDetail::class, 'discount_id');
    }
}
