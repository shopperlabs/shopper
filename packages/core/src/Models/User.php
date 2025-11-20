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
use Shopper\Core\Enum\GenderType;
use Shopper\Core\Models\Traits\HasDiscounts;
use Shopper\Core\Models\Traits\HasProfilePhoto;
use Shopper\Traits\TwoFactorAuthenticatable;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property-read int $id
 * @property-read string $full_name
 * @property-read string $picture
 * @property-read string|null $first_name
 * @property-read string $last_name
 * @property-read string $email
 * @property-read bool $opt_in
 * @property-read GenderType $gender
 * @property-read string $avatar_type
 * @property-read string|null $timezone
 * @property-read string|null $avatar_location
 * @property-read string|null $phone_number
 * @property-read string|null $last_login_ip
 * @property-read Carbon|null $email_verified_at
 * @property-read Carbon|null $birth_date
 * @property-read Carbon|null $last_login_at
 * @property-read string|null $two_factor_recovery_codes
 * @property-read string|null $two_factor_secret
 * @property-read \Illuminate\Support\Collection<int, Order> $orders
 * @property-read \Illuminate\Support\Collection<int, Address> $addresses
 */
class User extends Authenticatable
{
    use HasDiscounts;

    /** @use HasFactory<UserFactory> */
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

    public static function boot(): void
    {
        parent::boot();

        self::deleting(function (self $model): void {
            $model->roles()->detach();
            $model->addresses()->delete();
        });
    }

    public function isAdmin(): bool
    {
        return $this->hasRole(config('shopper.core.roles.admin'));
    }

    public function isManager(): bool
    {
        return $this->hasRole(config('shopper.core.roles.manager'));
    }

    public function isVerified(): bool
    {
        return $this->email_verified_at !== null;
    }

    /**
     * @param  Builder<User>  $query
     * @return Builder<User>
     */
    public function scopeCustomers(Builder $query): Builder
    {
        return $query->whereHas('roles', function (Builder $query): void {
            $query->where('name', config('shopper.core.roles.user'));
        });
    }

    /**
     * @param  Builder<User>  $query
     * @return Builder<User>
     */
    public function scopeAdministrators(Builder $query): Builder
    {
        return $query->whereHas('roles', function (Builder $query): void {
            $query->whereIn('name', [
                config('shopper.core.roles.admin'),
                config('shopper.core.roles.manager'),
            ]);
        });
    }

    /**
     * @return HasMany<Address, $this>
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    /**
     * @return HasMany<Order, $this>
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'birth_date' => 'datetime',
            'gender' => GenderType::class,
            'opt_in' => 'bool',
        ];
    }

    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn (): string => $this->first_name
                ? implode(' ', [$this->first_name, $this->last_name])
                : $this->last_name
        );
    }

    protected function birthDateFormatted(): Attribute
    {
        return Attribute::make(
            get: fn (): string => $this->birth_date // @phpstan-ignore-line
                ? $this->birth_date->isoFormat('%d, %B %Y')
                : __('shopper::words.not_defined')
        );
    }

    protected function rolesLabel(): Attribute
    {
        $roles = $this->roles()->pluck('display_name')->toArray();

        return Attribute::make(
            get: fn (): string => count($roles)
                ? implode(', ', array_map(fn (string $item): string => ucwords($item), $roles))
                : 'N/A'
        );
    }
}
