<?php

namespace Shopper\Framework\Models\User;

use Illuminate\Database\Eloquent\Model;
use Shopper\Framework\Models\System\Country;

class Address extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'last_name',
        'first_name',
        'company_name',
        'street_address',
        'street_address_plus',
        'zipcode',
        'city',
        'phone_number',
        'is_default',
        'type',
        'user_id',
        'country_id',
    ];

    /**
     * The attributes that should be cast.
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
        return shopper_table('user_addresses');
    }

    /**
     * Bootstrap the model and its traits.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($address) {
            if ($address->is_default) {
                $address->user->addresses()->where('type', $address->type)->update([
                    'is_default' => false
                ]);
            }
        });
    }

    /**
     * Define if an address is default or not.
     *
     * @return bool
     */
    public function isDefault()
    {
        return $this->is_default === true;
    }

    /**
     * Return the user's information.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(config('auth.providers.users.model', User::class), 'user_id');
    }

    /**
     * Return the address country information.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
}
