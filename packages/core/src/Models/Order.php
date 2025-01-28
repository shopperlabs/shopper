<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Shopper\Core\Database\Factories\OrderFactory;
use Shopper\Core\Enum\OrderStatus;
use Shopper\Core\Helpers\Price;
use Shopper\Core\Observers\OrderObserver;

/**
 * @property-read int $id
 * @property string $number
 * @property int $price_amount
 * @property string $notes
 * @property string $currency_code
 * @property int $total_amount
 * @property int | null $zone_id
 * @property int | null $shipping_address_id
 * @property int | null $payment_method_id
 * @property int | null $billing_address_id
 * @property int | null $customer_id
 * @property int | null $channel_id
 * @property int | null $parent_order_id
 * @property \Illuminate\Support\Carbon | null $canceled_at
 * @property OrderStatus $status
 * @property-read CarrierOption $shippingOption
 * @property-read OrderAddress | null $shippingAddress
 * @property-read OrderAddress | null $billingAddress
 * @property-read PaymentMethod | null $paymentMethod
 * @property-read Zone | null $zone
 * @property-read Channel | null $channel
 * @property-read Order | null $parent
 * @property-read \Illuminate\Foundation\Auth\User | User $customer
 * @property-read \Illuminate\Support\Collection<int, OrderItem> $items
 * @property-read \Illuminate\Support\Collection<int, Order> $children
 */
#[ObservedBy(OrderObserver::class)]
class Order extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'status' => OrderStatus::class,
        'canceled_at' => 'datetime',
    ];

    public function __construct(array $attributes = [])
    {
        if (! isset($attributes['status'])) {
            $this->setDefaultOrderStatus();
        }

        parent::__construct($attributes);
    }

    public function getTable(): string
    {
        return shopper_table('orders');
    }

    protected static function newFactory(): OrderFactory
    {
        return OrderFactory::new();
    }

    public function totalAmount(): Attribute
    {
        return Attribute::get(
            fn () => Price::from(amount: $this->total(), currency: $this->currency_code)
        );
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

    public function shippingAddress(): BelongsTo
    {
        return $this->belongsTo(OrderAddress::class, 'shipping_address_id');
    }

    public function billingAddress(): BelongsTo
    {
        return $this->belongsTo(OrderAddress::class, 'billing_address_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model', User::class), 'customer_id');
    }

    public function channel(): BelongsTo
    {
        return $this->belongsTo(config('shopper.models.channel'), 'channel_id');
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_order_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_order_id');
    }

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class, 'zone_id');
    }

    public function refund(): HasOne
    {
        return $this->hasOne(OrderRefund::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function shippingOption(): BelongsTo
    {
        return $this->belongsTo(CarrierOption::class, 'shipping_option_id');
    }

    protected function setDefaultOrderStatus(): void
    {
        $this->setRawAttributes(
            array_merge(
                $this->attributes,
                ['status' => OrderStatus::Pending]
            ),
            true
        );
    }
}
