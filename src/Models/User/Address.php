<?php

namespace Shopper\Framework\Models\User;

use Illuminate\Database\Eloquent\Model;
use Shopper\Framework\Models\System\Country;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    public const TYPE_BILLING = 'billing';

    public const TYPE_SHIPPING = 'shipping';

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
     * The dynamic attributes from mutators that should be returned with the user object.
     *
     * @var array
     */
    protected $appends = [
        'full_name',
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
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'country',
    ];

    /**
     * Get the table associated with the model.
     */
    public function getTable(): string
    {
        return shopper_table('user_addresses');
    }

    /**
     * Return Address Full Name.
     */
    public function getFullNameAttribute(): string
    {
        return $this->last_name
            ? $this->first_name . ' ' . $this->last_name
            : $this->first_name;
    }

    /**
     * Define if an address is default or not.
     */
    public function isDefault(): bool
    {
        return $this->is_default === true;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model', User::class), 'user_id');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    /**
     * Bootstrap the model and its traits.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($address) {
            if ($address->is_default) {
                $address->user->addresses()->where('type', $address->type)->update([
                    'is_default' => false,
                ]);
            }
        });
    }
}
