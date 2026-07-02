<?php

declare(strict_types=1);

namespace Shopper\Cart\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Shopper\Cart\Database\Factories\CartFactory;
use Shopper\Cart\Models\Contracts\Cart as CartContract;
use Shopper\Core\Enum\AddressType;
use Shopper\Core\Models\Channel;
use Shopper\Core\Models\Order;
use Shopper\Core\Models\PaymentMethod;
use Shopper\Core\Models\Traits\HasPublicId;
use Shopper\Core\Models\Zone;
use Shopper\Core\Traits\HasModelContract;

/**
 * @property-read int $id
 * @property-read ?string $public_id
 * @property-read string $currency_code
 * @property-read ?string $email
 * @property-read ?CarbonInterface $calculated_at
 * @property-read ?CarbonInterface $completed_at
 * @property-read ?array<string, mixed> $metadata
 * @property-read ?int $customer_id
 * @property-read ?int $channel_id
 * @property-read ?int $zone_id
 * @property-read ?int $payment_method_id
 * @property-read ?int $order_id
 * @property-read ?string $shipping_option_id
 * @property-read ?int $shipping_amount
 * @property-read ?array<string, mixed> $payment_session
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 * @property-read Collection<int, CartLine> $lines
 * @property-read Collection<int, CartPromotion> $promotions
 * @property-read Collection<int, CartAddress> $addresses
 * @property-read ?Model $customer
 * @property-read ?Channel $channel
 * @property-read ?Zone $zone
 * @property-read ?PaymentMethod $paymentMethod
 * @property-read ?Order $order
 */
class Cart extends Model implements CartContract
{
    /** @use HasFactory<CartFactory> */
    use HasFactory;

    use HasModelContract;
    use HasPublicId;

    /**
     * @var list<string>
     */
    protected $guarded = ['payment_session'];

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
     * @return HasMany<CartPromotion, $this>
     */
    public function promotions(): HasMany
    {
        return $this->hasMany(CartPromotion::class, 'cart_id');
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

    /**
     * @return BelongsTo<PaymentMethod, $this>
     */
    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    /**
     * @return BelongsTo<Order, $this>
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    protected static function newFactory(): CartFactory
    {
        return CartFactory::new();
    }

    protected function casts(): array
    {
        return [
            'calculated_at' => 'datetime',
            'completed_at' => 'datetime',
            'metadata' => 'array',
            'payment_session' => 'array',
        ];
    }
}
