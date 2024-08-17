<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Shopper\Core\Database\Factories\UserFactory;
use Shopper\Core\Traits\CanHaveDiscount;
use Shopper\Core\Traits\HasProfilePhoto;
use Shopper\Traits\TwoFactorAuthenticatable;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property-read int $id
 * @property-read string $full_name
 * @property-read string $picture
 * @property string|null $first_name
 * @property string $last_name
 * @property string $email
 * @property string $avatar_type
 * @property string|null $avatar_location
 * @property string|null $phone_number
 * @property Carbon|null $email_verified_at
 * @property Carbon|null $birth_date
 * @property string|null $two_factor_recovery_codes
 * @property string|null $two_factor_secret
 */
class User extends Authenticatable
{
    use CanHaveDiscount;
    use HasFactory;
    use HasProfilePhoto;
    use HasRoles;
    use Notifiable;
    use TwoFactorAuthenticatable;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
        'last_login_at',
        'last_login_ip',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'birth_date' => 'datetime',
    ];

    protected $appends = [
        'full_name',
        'picture',
        'roles_label',
        'birth_date_formatted',
    ];

    public static function boot(): void
    {
        parent::boot();

        self::deleting(function ($model): void {
            $model->roles()->detach();
            $model->addresses()->delete();
        });
    }

    /**
     * @return UserFactory|null
     */
    protected static function newFactory()
    {
        return UserFactory::new();
    }

    public function isAdmin(): bool
    {
        return $this->hasRole(config('shopper.core.users.admin_role'));
    }

    public function isManager(): bool
    {
        return $this->hasRole(config('shopper.core.users.manager_role'));
    }

    public function isVerified(): bool
    {
        return $this->email_verified_at !== null;
    }

    public function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->first_name
                ? $this->first_name . ' ' . $this->last_name
                : $this->last_name
        );
    }

    public function birthDateFormatted(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->birth_date
                ? $this->birth_date->isoFormat('%d, %B %Y')
                : __('shopper::words.not_defined')
        );
    }

    public function rolesLabel(): Attribute
    {
        $roles = $this->roles()->pluck('display_name')->toArray();

        return Attribute::make(
            get: fn () => count($roles)
                ? implode(', ', array_map(fn ($item) => ucwords($item), $roles))
                : 'N/A'
        );
    }

    public function scopeCustomers(Builder $query): Builder
    {
        return $query->whereHas('roles', function (Builder $query): void {
            $query->where('name', config('shopper.core.users.default_role'));
        });
    }

    public function scopeAdministrators(Builder $query): Builder
    {
        return $query->whereHas('roles', function (Builder $query): void {
            $query->whereIn('name', [
                config('shopper.core.users.admin_role'),
                config('shopper.core.users.manager_role'),
            ]);
        });
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'customer_id');
    }
}
