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
    ];

    /**
     * Get discounts related to a shop.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }
}
