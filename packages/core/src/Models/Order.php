<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Carbon\CarbonInterface;
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
 * @property-read ?int $payment_method_id
 * @property-read ?int $billing_address_id
 * @property-read ?int $customer_id
 * @property-read ?int $channel_id
 * @property-read ?int $parent_order_id
 * @property-read ?CarbonInterface $canceled_at
 * @property-read OrderStatus $status
 * @property-read CarrierOption $shippingOption
 * @property-read ?OrderAddress $shippingAddress
 * @property-read ?OrderAddress $billingAddress
 * @property-read ?PaymentMethod $paymentMethod
 * @property-read ?Zone $zone
 * @property-read ?Channel $channel
 * @property-read ?static $parent
 * @property-read Model&ShopperUser $customer
 * @property-read Collection<int, OrderItem> $items
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
                ['status' => OrderStatus::Pending]
            ),
            true
        );
    }

    public function getTable(): string
    {
        return shopper_table('orders');
    }

    public function total(): int
    {
        return $this->items->sum('total');
    }

    public function canBeCancelled(): bool
    {
        return $this->status === OrderStatus::Completed || $this->status === OrderStatus::New;
    }

    public function isNotCancelled(): bool
    {
        return $this->status !== OrderStatus::Cancelled;
    }

    public function isPending(): bool
    {
        return $this->status === OrderStatus::Pending;
    }

    public function isRegister(): bool
    {
        return $this->status === OrderStatus::Register;
    }

    public function isShipped(): bool
    {
        return $this->status === OrderStatus::Shipped;
    }

    public function isCompleted(): bool
    {
        return $this->status === OrderStatus::Completed;
    }

    public function isPaid(): bool
    {
        return $this->status === OrderStatus::Paid;
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
     * @return BelongsTo<CarrierOption, $this>
     */
    public function shippingOption(): BelongsTo
    {
        return $this->belongsTo(CarrierOption::class, 'shipping_option_id');
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
            'canceled_at' => 'datetime',
        ];
    }
}
