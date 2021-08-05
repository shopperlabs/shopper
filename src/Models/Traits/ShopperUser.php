<?php

namespace Shopper\Framework\Models\Traits;

use function count;
use Laravel\Cashier\Billable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Storage;
use Shopper\Framework\Models\User\Address;
use Illuminate\Database\Eloquent\SoftDeletes;
use Shopper\Framework\Models\Shop\Order\Order;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Shopper\Framework\Services\TwoFactor\TwoFactorAuthenticatable;

/**
 * Trait ShopperUser.
 *
 * @mixin \Shopper\Framework\Models\User\User
 */
trait ShopperUser
{
    use HasRoles;
    use CanHaveDiscount;
    use SoftDeletes;
    use TwoFactorAuthenticatable;
    use Billable;

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
     */
    public static function boot()
    {
        Authenticatable::boot();

        static::deleting(function ($model) {
            $model->addresses()->delete();
            $model->roles()->detach();
            $model->orders()->delete();
        });
    }

    /**
     * Get the table associated with the model.
     */
    public function getTable(): string
    {
        return shopper_table('users');
    }

    /**
     * Define if user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->hasRole(config('shopper.system.users.admin_role'));
    }

    /**
     * Define if an user account is verified.
     */
    public function isVerified(): bool
    {
        return $this->email_verified_at !== null;
    }

    /**
     * Return User Full Name.
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
     */
    public function getRolesLabelAttribute(): string
    {
        $roles = $this->roles()->pluck('display_name')->toArray();

        if (count($roles)) {
            return implode(', ', array_map(fn ($item) => ucwords($item), $roles));
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
