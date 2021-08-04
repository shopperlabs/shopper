<?php

namespace Shopper\Framework\Models\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Storage;
use Laravel\Cashier\Billable;
use Shopper\Framework\Models\Shop\Order\Order;
use Shopper\Framework\Models\User\Address;
use Shopper\Framework\Services\TwoFactor\TwoFactorAuthenticatable;
use Spatie\Permission\Traits\HasRoles;

/**
 * Trait ShopperUser
 * @package Shopper\Framework\Models\Traits
 * @mixin \Shopper\Framework\Models\User\User
 */
trait ShopperUser
{
    use HasRoles,
        CanHaveDiscount,
        SoftDeletes,
        TwoFactorAuthenticatable,
        Billable;

    /**
     * ShopperUser constructor.
     */
    public function __construct()
    {
        $this->makeHidden([
            'password',
            'remember_token',
            'last_login_at',
            'last_login_ip',
            'two_factor_recovery_codes',
            'two_factor_secret',
        ]);

        $this->setAppends([
            'full_name',
            'picture',
            'roles_label',
            'birth_date_formatted',
        ]);

        $this->mergeCasts([
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'birth_date' => 'datetime',
        ]);
    }

    /**
     * Bootstrap the model and its traits.
     *
     * @return void
     */
    public static function boot()
    {
        Authenticatable::boot();

        static::deleting(function($model) {
            $model->addresses()->delete();
            $model->roles()->detach();
            $model->orders()->delete();
        });
    }

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable(): string
    {
        return shopper_table('users');
    }

    /**
     * Define if user is an admin
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->hasRole(config('shopper.system.users.admin_role'));
    }

    /**
     * Define if an user account is verified.
     *
     * @return bool
     */
    public function isVerified(): bool
    {
        return $this->email_verified_at !== null;
    }

    /**
     * Return User Full Name.
     *
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return $this->last_name
            ? $this->first_name . ' ' . $this->last_name
            : $this->first_name;
    }

    public function getBirthDateFormattedAttribute(): string
    {
        if ($this->birth_date) {
            return $this->birth_date->formatLocalized('%d, %B %Y');
        }

        return __('Not defined');
    }

    /**
     * Get User roles name.
     *
     * @return string
     */
    public function getRolesLabelAttribute(): string
    {
        $roles = $this->roles()->pluck('display_name')->toArray();

        if (\count($roles)) {
            return implode(', ', array_map(function ($item) {
                return ucwords($item);
            }, $roles));
        }

        return 'N/A';
    }

    public function getPictureAttribute(): string
    {
        switch ($this->avatar_type) {
            case 'gravatar':
                return gravatar()->get($this->email);

            case 'storage':
                return Storage::disk(config('shopper.system.storage.disks.avatars'))->url($this->avatar_location);
        }
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
