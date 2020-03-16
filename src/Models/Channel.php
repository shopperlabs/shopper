<?php

namespace Shopper\Framework\Models;

use Illuminate\Database\Eloquent\Model;
use Shopper\Framework\Models\Shop\Shop;

class Channel extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'timezone',
        'url',
        'shop_id',
    ];

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
        return shopper_table('channels');
    }

    /**
     * Get channel shop.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }
}
