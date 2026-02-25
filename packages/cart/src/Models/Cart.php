<?php

declare(strict_types=1);

namespace Shopper\Cart\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Shopper\Core\Enum\AddressType;
use Shopper\Core\Models\Channel;
use Shopper\Core\Models\Contracts\Cart as CartContract;
use Shopper\Core\Models\Zone;
use Shopper\Core\Traits\HasModelContract;

/**
 * @property-read int $id
 * @property-read string $currency_code
 * @property-read ?string $coupon_code
 * @property-read ?CarbonInterface $completed_at
 * @property-read ?array<string, mixed> $metadata
 * @property-read ?int $customer_id
 * @property-read ?int $channel_id
 * @property-read ?int $zone_id
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 * @property-read Collection<int, CartLine> $lines
 * @property-read Collection<int, CartAddress> $addresses
 * @property-read ?Model $customer
 * @property-read ?Channel $channel
 * @property-read ?Zone $zone
 */
class Cart extends Model implements CartContract
{
    use HasModelContract;

    protected $guarded = [];

    public static function configuredClass(): string
    {
        return config('shopper.cart.models.cart', static::class);
    }

    public function getTable(): string
    {
        return shopper_table('carts');
    }

    public function isCompleted(): bool
    {
        return $this->completed_at !== null;
    }

    public function shippingAddress(): ?CartAddress
    {
        return $this->addresses->firstWhere('type', AddressType::Shipping);
    }

    public function billingAddress(): ?CartAddress
    {
        return $this->addresses->firstWhere('type', AddressType::Billing);
    }

    /**
     * @return HasMany<CartLine, $this>
     */
    public function lines(): HasMany
    {
        return $this->hasMany(config('shopper.cart.models.cart_line', CartLine::class));
    }

    /**
     * @return HasMany<CartAddress, $this>
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(CartAddress::class);
    }

    /**
     * @return BelongsTo<Model, $this>
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'customer_id');
    }

    /**
     * @return BelongsTo<Channel, $this>
     */
    public function channel(): BelongsTo
    {
        return $this->belongsTo(config('shopper.models.channel'), 'channel_id');
    }

    /**
     * @return BelongsTo<Zone, $this>
     */
    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }

    protected function casts(): array
    {
        return [
            'completed_at' => 'datetime',
            'metadata' => 'array',
        ];
    }
}
