<?php

declare(strict_types=1);

namespace Shopper\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

interface ShopperUser
{
    public function isAdmin(): bool;

    public function isManager(): bool;

    public function isVerified(): bool;

    /**
     * @return HasMany<Model, static>
     */
    public function orders(): HasMany;

    /**
     * @return HasMany<Model, static>
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
