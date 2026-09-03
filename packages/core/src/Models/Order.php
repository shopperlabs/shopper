<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
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
use Shopper\Core\Models\Contracts\Order as OrderContract;
use Shopper\Core\Models\Traits\HasPublicId;
use Shopper\Core\Models\Traits\Searchable;
use Shopper\Core\Traits\HasModelContract;
use Shopper\Core\Traits\HasOrderStatusTransitions;
use Shopper\Core\Traits\HasPaymentStatusTransitions;

/**
 * @property-read int $id
 * @property-read ?string $public_id
 * @property-read ?string $number
 * @property-read int $price_amount
 * @property-read ?int $tax_amount
 * @property-read ?int $shipping_amount
 * @property-read string $notes
 * @property-read string $currency_code
 * @property-read ?string $email
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
 * @property-read ?OrderRefund $refund
 * @property-read ?Discount $discount
 * @property-read ?PaymentMethod $paymentMethod
 * @property-read ?Zone $zone
 * @property-read ?Channel $channel
 * @property-read ?static $parent
 * @property-read ?Model $customer
 * @property-read Collection<int, OrderItem> $items
 * @property-read Collection<int, OrderPromotion> $promotions
 * @property-read Collection<int, OrderShipping> $shippings
 * @property-read Collection<int, Order> $children
 */
class Order extends Model implements OrderContract
{
    /** @use HasFactory<OrderFactory> */
    use HasFactory;

    use HasModelContract;
    use HasOrderStatusTransitions;
    use HasPaymentStatusTransitions;
    use HasPublicId;
    use Searchable;
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

    public static function configuredClass(): string
    {
        return config('shopper.models.order', static::class);
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
            && ! in_array($this->shipping_status, [
                ShippingStatus::Delivered,
                ShippingStatus::PartiallyDelivered,
                ShippingStatus::Returned,
                ShippingStatus::PartiallyReturned,
            ], true);
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

    public function isAwaitingPayment(): bool
    {
        return $this->payment_status === PaymentStatus::Pending
            && $this->status === OrderStatus::New;
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
        return $this->belongsTo(static::class, 'parent_order_id');
    }

    /**
     * @return HasMany<static, $this>
     */
    public function children(): HasMany
    {
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
     * @return BelongsTo<Discount, $this>
     */
    public function discount(): BelongsTo
    {
        return $this->belongsTo(Discount::class, 'discount_id');
    }

    /**
     * @return HasMany<OrderPromotion, $this>
     */
    public function promotions(): HasMany
    {
        return $this->hasMany(OrderPromotion::class, 'order_id');
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

    protected static function booted(): void
    {
        // The number derives from the row's own id after insert, so two
        // concurrent checkouts can never generate the same number the way a
        // max-plus-one read in `creating` could. The unique index backs it up.
        static::created(function (Order $order): void {
            if (blank($order->getAttribute('number'))) {
                $order->updateQuietly(['number' => generate_number($order->id)]);
            }
        });
    }

    protected static function newFactory(): OrderFactory
    {
        return OrderFactory::new();
    }

    /**
     * @param  Builder<Order>  $query
     * @return Builder<Order>
     */
    #[Scope]
    protected function notArchived(Builder $query): Builder
    {
        return $query->where('status', '!=', OrderStatus::Archived);
    }

    /**
     * @param  Builder<Order>  $query
     * @return Builder<Order>
     */
    #[Scope]
    protected function archived(Builder $query): Builder
    {
        return $query->where('status', OrderStatus::Archived);
    }

    /**
     * @param  Builder<Order>  $query
     * @return Builder<Order>
     */
    #[Scope]
    protected function awaitingPayment(Builder $query): Builder
    {
        return $query
            ->where('payment_status', PaymentStatus::Pending)
            ->where('status', OrderStatus::New);
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
