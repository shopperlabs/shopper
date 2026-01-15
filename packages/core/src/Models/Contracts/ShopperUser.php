<?php

declare(strict_types=1);

namespace Shopper\Core\Models\Contracts;

use Carbon\CarbonInterface;
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
 * @property-read ?string $first_name
 * @property-read string $last_name
 * @property-read string $email
 * @property-read bool $opt_in
 * @property-read GenderType $gender
 * @property-read string $avatar_type
 * @property-read ?string $timezone
 * @property-read ?string $avatar_location
 * @property-read ?string $phone_number
 * @property-read ?string $last_login_ip
 * @property-read ?CarbonInterface $email_verified_at
 * @property-read ?CarbonInterface $birth_date
 * @property-read ?CarbonInterface $last_login_at
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
