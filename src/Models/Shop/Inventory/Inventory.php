<?php

namespace Shopper\Framework\Models\Shop\Inventory;

use Illuminate\Database\Eloquent\Model;
use Shopper\Framework\Models\System\Country;

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
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($inventory) {
            if ($inventory->is_default) {
                static::query()->update(['is_default' => false]);
            }
        });

        static::updating(function ($inventory) {
            if ($inventory->is_default) {
                static::query()->update(['is_default' => false]);
            }
        });
    }

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

    /**
     * Country relationship for the inventory.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
}
