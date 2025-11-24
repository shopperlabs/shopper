<?php

declare(strict_types=1);

namespace Shopper\Core\Contracts;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Shopper\Core\Enum\GenderType;
use Shopper\Core\Models\Address;
use Shopper\Core\Models\Order;

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
 * @property-read \Illuminate\Support\Carbon|null $email_verified_at
 * @property-read \Illuminate\Support\Carbon|null $birth_date
 * @property-read \Illuminate\Support\Carbon|null $last_login_at
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 * @mixin \Illuminate\Notifications\Notifiable
 * @mixin \Spatie\Permission\Traits\HasRoles
 */
interface ShopperUser extends Authenticatable
{
    public function isAdmin(): bool;

    public function isManager(): bool;

    public function isVerified(): bool;

    /**
     * @return HasMany<Order, $this>
     */
    public function orders(): HasMany;

    /**
     * @return HasMany<Address, $this>
     */
    public function addresses(): HasMany;

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeAdministrators(Builder $query): Builder;

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeCustomers(Builder $query): Builder;
}
