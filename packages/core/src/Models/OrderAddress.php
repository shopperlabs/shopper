<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Shopper\Core\Database\Factories\OrderAddressFactory;

/**
 * @property-read int $id
 * @property-read string $last_name
 * @property-read string $first_name
 * @property-read string $full_name
 * @property-read string $street_address
 * @property-read string|null $street_address_plus
 * @property-read string $postal_code
 * @property-read string $city
 * @property-read string|null $company
 * @property-read string|null $phone
 * @property-read string|null $country_name
 */
class OrderAddress extends Model
{
    /** @use HasFactory<OrderAddressFactory> */
    use HasFactory;

    protected $guarded = [];

    public function getTable(): string
    {
        return shopper_table('order_addresses');
    }

    /**
     * @return HasMany<Order, $this>
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function customer(): BelongsTo
    {
        // @phpstan-ignore-next-line
        return $this->belongsTo(config('auth.providers.users.model', User::class), 'customer_id');
    }

    protected static function newFactory(): OrderAddressFactory
    {
        return OrderAddressFactory::new();
    }

    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn (): string => $this->first_name
                ? implode(' ', [$this->first_name, $this->last_name])
                : $this->last_name
        );
    }
}
