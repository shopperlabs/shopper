<?php

declare(strict_types=1);

namespace Shopper\Cart\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
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
use Shopper\Core\Pricing\PricingContext;
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
 * @property-read ?string $payment_reference
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
    protected $guarded = ['payment_session', 'payment_reference'];

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

    public function holdsProviderPaymentSession(): bool
    {
        return $this->payment_session !== null && ($this->payment_session['driver'] ?? 'manual') !== 'manual';
    }

    public function heldTaxInclusive(): ?bool
    {
        if (! $this->holdsProviderPaymentSession() || ! isset($this->payment_session['tax_inclusive'])) {
            return null;
        }

        return (bool) $this->payment_session['tax_inclusive'];
    }

    public function belongsToCustomer(int|string|null $customerId): bool
    {
        return $this->customer_id !== null && (string) $this->customer_id === (string) $customerId;
    }

    public function pricingContext(int $quantity = 1, ?string $currencyCode = null, ?int $productQuantity = null): PricingContext
    {
        return new PricingContext(
            currencyCode: $currencyCode ?? $this->currency_code,
            customerId: $this->customer_id,
            quantity: $quantity,
            channelId: $this->channel_id,
            zoneId: $this->zone_id,
            productQuantity: $productQuantity,
        );
    }

    /**
     * @param  array<string, int>|null  $productQuantities
     */
    public function pricingContextFor(CartLine $line, ?string $currencyCode = null, ?int $quantity = null, ?array $productQuantities = null): PricingContext
    {
        $key = $line->productKey();
        $quantity ??= $line->quantity;

        return $this->pricingContext(
            quantity: $quantity,
            currencyCode: $currencyCode,
            productQuantity: $quantity + ($productQuantities === null
                ? (int) $this->lines
                    ->filter(fn (CartLine $sibling): bool => ! $sibling->is($line) && $sibling->productKey() === $key)
                    ->sum('quantity')
                : $productQuantities[$key] - $line->quantity),
        );
    }

    /**
     * @return array<string, int>
     */
    public function productQuantities(): array
    {
        $quantities = [];

        foreach ($this->lines as $line) {
            $key = $line->productKey();
            $quantities[$key] = ($quantities[$key] ?? 0) + $line->quantity;
        }

        return $quantities;
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

    /**
     * @param  Builder<Cart>  $query
     * @return Builder<Cart>
     */
    #[Scope]
    protected function holdingPaymentReference(Builder $query, string $reference): Builder
    {
        return $query->whereNull('completed_at')->where('payment_reference', $reference);
    }

    /**
     * @param  Builder<Cart>  $query
     * @return Builder<Cart>
     */
    #[Scope]
    protected function forPayment(Builder $query, string $reference, ?string $publicId): Builder
    {
        return $query->where(fn (Builder $query): Builder => $query
            ->where(fn (Builder $query): Builder => $query->holdingPaymentReference($reference))
            ->when($publicId, fn (Builder $query, string $publicId): Builder => $query->orWhere('public_id', $publicId)));
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
