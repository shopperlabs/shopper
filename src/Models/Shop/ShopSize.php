<?php

namespace Shopper\Framework\Models\Shop;

use Illuminate\Database\Eloquent\Model;

class ShopSize extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'size', 'description', 'max_products'];
}
