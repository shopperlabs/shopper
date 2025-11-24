<?php

declare(strict_types=1);

namespace Shopper\Core\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Shopper\Core\Enum\GenderType;
use Shopper\Core\Models\Address;
use Shopper\Core\Models\Order;
use Shopper\Core\Models\Traits\HasDiscounts;
use Shopper\Core\Models\Traits\HasProfilePhoto;
use Shopper\Traits\TwoFactorAuthenticatable;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property-read string|null $two_factor_recovery_codes
 * @property-read string|null $two_factor_secret
 * @property-read \Illuminate\Support\Collection<int, Order> $orders
 * @property-read \Illuminate\Support\Collection<int, Address> $addresses
 */
trait ShopperUser
{
    use HasDiscounts;
    use HasProfilePhoto;
    use HasRoles;
    use TwoFactorAuthenticatable;

    public static function bootShopperUser(): void
    {
        static::deleting(function (self $model): void {
            $model->roles()->detach();
            $model->addresses()->delete();
        });
    }

    public function initializeShopperUser(): void
    {
        $this->mergeCasts([
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'birth_date' => 'datetime',
            'gender' => GenderType::class,
            'opt_in' => 'bool',
        ]);
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

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeCustomers(Builder $query): Builder
    {
        return $query->whereHas('roles', function (Builder $query): void {
            $query->where('name', config('shopper.core.roles.user'));
        });
    }

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
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
