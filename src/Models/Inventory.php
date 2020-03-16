<?php

namespace Shopper\Framework\Models;

use Illuminate\Database\Eloquent\Model;
use Shopper\Framework\Models\Shop\Shop;

class Inventory extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'code',
        'description',
        'email',
        'phone_number',
        'shop_id',
        'country',
        'city',
        'street',
        'postcode',
        'priority',
        'latitude',
        'latitude',
        'is_default',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
       'is_default' => 'boolean'
    ];

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
        return shopper_table('inventories');
    }

    /**
     * Get inventory shop.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }
}
