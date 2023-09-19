<?php

declare(strict_types=1);

namespace Shopper\Framework\Models\User;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Shopper\Framework\Models\Shop\Order\Order;
use Shopper\Framework\Models\Traits\CanHaveDiscount;
use Shopper\Framework\Models\Traits\HasProfilePhoto;
use Shopper\Framework\Services\TwoFactor\TwoFactorAuthenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use CanHaveDiscount;
    use HasRoles;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use SoftDeletes;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var bool|string[]
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'last_login_at',
        'last_login_ip',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'birth_date' => 'datetime',
    ];

    /**
     * The dynamic attributes from mutators that should be returned with the user object.
     *
     * @var array
     */
    protected $appends = [
        'full_name',
        'picture',
        'roles_label',
        'birth_date_formatted',
    ];

    /**
     * Bootstrap the model and its traits.
     */
    public static function boot()
    {
        parent::boot();

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

    public function isVerified(): bool
    {
        return $this->email_verified_at !== null;
    }

    public function getFullNameAttribute(): string
    {
        return $this->first_name
            ? $this->first_name . ' ' . $this->last_name
            : $this->last_name;
    }

    public function getBirthDateFormattedAttribute(): string
    {
        if ($this->birth_date) {
            return $this->birth_date->formatLocalized('%d, %B %Y');
        }

        return __('shopper::words.not_defined');
    }

    public function getRolesLabelAttribute(): string
    {
        $roles = $this->roles()->pluck('display_name')->toArray();

        if (count($roles)) {
            return implode(', ', array_map(fn ($item) => ucwords($item), $roles));
        }

        return 'N/A';
    }

    public function scopeResearch(Builder $query, $term): Builder
    {
        return $query->where(
            fn ($query) => $query->where('last_name', 'like', '%' . $term . '%')
                ->orWhere('first_name', 'like', '%' . $term . '%')
                ->orWhere('email', 'like', '%' . $term . '%')
        );
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
