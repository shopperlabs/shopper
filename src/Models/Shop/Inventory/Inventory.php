<?php

namespace Shopper\Framework\Models\Shop\Inventory;

use Illuminate\Database\Eloquent\Model;

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
        'street_address',
        'street_address_plus',
        'zipcode',
        'city',
        'priority',
        'longitude',
        'latitude',
        'is_default',
        'country_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_default' => 'boolean',
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
}
