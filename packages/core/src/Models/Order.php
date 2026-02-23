<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Shopper\Core\Database\Factories\OrderFactory;
use Shopper\Core\Enum\OrderStatus;
use Shopper\Core\Enum\PaymentStatus;
use Shopper\Core\Enum\ShippingStatus;
use Shopper\Core\Helpers\Price;
use Shopper\Core\Models\Contracts\Order as OrderContract;
use Shopper\Core\Models\Contracts\ShopperUser;
use Shopper\Core\Traits\HasModelContract;

/**
 * @property-read int $id
 * @property-read string $number
 * @property-read int $price_amount
 * @property-read string $notes
 * @property-read string $currency_code
 * @property-read int $total_amount
 * @property-read ?int $zone_id
 * @property-read ?int $shipping_address_id
 * @property-read ?int $shipping_option_id
 * @property-read ?int $payment_method_id
 * @property-read ?int $billing_address_id
 * @property-read ?int $customer_id
 * @property-read ?int $channel_id
 * @property-read ?int $parent_order_id
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 * @property-read ?CarbonInterface $deleted_at
 * @property-read ?CarbonInterface $cancelled_at
 * @property-read ?CarbonInterface $archived_at
 * @property-read OrderStatus $status
 * @property-read PaymentStatus $payment_status
 * @property-read ShippingStatus $shipping_status
 * @property-read array<string, mixed>|null $metadata
 * @property-read ?CarrierOption $shippingOption
 * @property-read ?OrderAddress $shippingAddress
 * @property-read ?OrderAddress $billingAddress
 * @property-read ?PaymentMethod $paymentMethod
 * @property-read ?Zone $zone
 * @property-read ?Channel $channel
 * @property-read ?static $parent
 * @property-read Model&ShopperUser $customer
 * @property-read Collection<int, OrderItem> $items
 * @property-read Collection<int, OrderShipping> $shippings
 * @property-read Collection<int, Order> $children
 */
class Order extends Model implements OrderContract
{
    /** @use HasFactory<OrderFactory> */
    use HasFactory;

    use HasModelContract;
    use SoftDeletes;

    protected $guarded = [];

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function __construct(array $attributes = [])
    {
        if (! isset($attributes['status'])) {
            $this->setDefaultOrderStatus();
        }

        parent::__construct($attributes);
    }

    public static function configKey(): string
    {
        return 'order';
    }

    public function setDefaultOrderStatus(): void
    {
        $this->setRawAttributes(
            array_merge(
                $this->attributes,
                [
                    'status' => OrderStatus::New,
                    'payment_status' => PaymentStatus::Pending,
                    'shipping_status' => ShippingStatus::Unfulfilled,
                ]
            ),
            true
        );
    }

    public function getTable(): string
    {
        return shopper_table('orders');
    }

    public function total(): float|int
    {
        return $this->items->sum('total');
    }

    public function canBeCancelled(): bool
    {
        return ! in_array($this->status, [
            OrderStatus::Cancelled,
            OrderStatus::Archived,
        ], true)
            && $this->shipping_status === ShippingStatus::Unfulfilled;
    }

    public function isNotCancelled(): bool
    {
        return $this->status !== OrderStatus::Cancelled;
    }

    public function isNew(): bool
    {
        return $this->status === OrderStatus::New;
    }

    public function isProcessing(): bool
    {
        return $this->status === OrderStatus::Processing;
    }

    public function isCompleted(): bool
    {
        return $this->status === OrderStatus::Completed;
    }

    public function isArchived(): bool
    {
        return $this->status === OrderStatus::Archived;
    }

    public function isPaid(): bool
    {
        return $this->payment_status === PaymentStatus::Paid;
    }

    public function isPaymentPending(): bool
    {
        return $this->payment_status === PaymentStatus::Pending;
    }

    public function isPaymentAuthorized(): bool
    {
        return $this->payment_status === PaymentStatus::Authorized;
    }

    public function isRefunded(): bool
    {
        return $this->payment_status === PaymentStatus::Refunded;
    }

    public function isShipped(): bool
    {
        return $this->shipping_status === ShippingStatus::Shipped;
    }

    public function isShippingPending(): bool
    {
        return $this->shipping_status === ShippingStatus::Unfulfilled;
    }

    /**
     * @return BelongsTo<OrderAddress, $this>
     */
    public function shippingAddress(): BelongsTo
    {
        return $this->belongsTo(OrderAddress::class, 'shipping_address_id');
    }

    /**
     * @return BelongsTo<OrderAddress, $this>
     */
    public function billingAddress(): BelongsTo
    {
        return $this->belongsTo(OrderAddress::class, 'billing_address_id');
    }

    /**
     * @return BelongsTo<Model&ShopperUser, $this>
     */
    public function customer(): BelongsTo
    {
        // @phpstan-ignore-next-line
        return $this->belongsTo(config('auth.providers.users.model'), 'customer_id');
    }

    /**
     * @return BelongsTo<Channel, $this>
     */
    public function channel(): BelongsTo
    {
        // @phpstan-ignore-next-line
        return $this->belongsTo(config('shopper.models.channel'), 'channel_id');
    }

    /**
     * @return BelongsTo<PaymentMethod, $this>
     */
    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    /**
     * @return BelongsTo<static, $this>
     */
    public function parent(): BelongsTo
    {
        // @phpstan-ignore-next-line
        return $this->belongsTo(static::class, 'parent_order_id');
    }

    /**
     * @return HasMany<static, $this>
     */
    public function children(): HasMany
    {
        // @phpstan-ignore-next-line
        return $this->hasMany(static::class, 'parent_order_id');
    }

    /**
     * @return BelongsTo<Zone, $this>
     */
    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class, 'zone_id');
    }

    /**
     * @return HasOne<OrderRefund, $this>
     */
    public function refund(): HasOne
    {
        return $this->hasOne(OrderRefund::class);
    }

    /**
     * @return HasMany<OrderItem, $this>
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * @return HasMany<OrderShipping, $this>
     */
    public function shippings(): HasMany
    {
        return $this->hasMany(OrderShipping::class);
    }

    /**
     * @return BelongsTo<CarrierOption, $this>
     */
    public function shippingOption(): BelongsTo
    {
        return $this->belongsTo(CarrierOption::class, 'shipping_option_id');
    }

    /**
     * @param  Builder<Order>  $query
     * @return Builder<Order>
     */
    public function scopeArchived(Builder $query): Builder
    {
        return $query->where('status', OrderStatus::Archived);
    }

    /**
     * @param  Builder<Order>  $query
     * @return Builder<Order>
     */
    public function scopeNotArchived(Builder $query): Builder
    {
        return $query->where('status', '!=', OrderStatus::Archived);
    }

    protected static function newFactory(): OrderFactory
    {
        return OrderFactory::new();
    }

    protected function totalAmount(): Attribute
    {
        return Attribute::get(
            fn (): Price => Price::from(amount: $this->total(), currency: $this->currency_code)
        );
    }

    protected function casts(): array
    {
        return [
            'status' => OrderStatus::class,
            'payment_status' => PaymentStatus::class,
            'shipping_status' => ShippingStatus::class,
            'cancelled_at' => 'datetime',
            'archived_at' => 'datetime',
        ];
    }
}
