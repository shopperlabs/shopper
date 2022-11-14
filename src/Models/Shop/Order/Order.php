<?php

namespace Shopper\Framework\Models\Shop\Order;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Shopper\Framework\Models\Shop\PaymentMethod;
use Shopper\Framework\Models\Traits\HasPrice;
use Shopper\Framework\Models\User\Address;
use Shopper\Framework\Models\User\User;

class Order extends Model
{
    use HasFactory;
    use HasPrice;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'number',
        'status',
        'currency',
        'shipping_total',
        'shipping_method',
        'notes',
        'parent_order_id',
        'shipping_address_id',
        'payment_method_id',
        'price_amount',
        'user_id',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'total',
        'status_classes',
        'formatted_status',
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

    public function getTotalAttribute(): string
    {
        return $this->formattedPrice($this->total());
    }

    public function getStatusClassesAttribute(): string
    {
        return match ($this->status) {
            OrderStatus::PENDING => 'border-yellow-200 bg-yellow-100 text-yellow-800',
            OrderStatus::REGISTER => 'border-blue-200 bg-blue-100 text-blue-800',
            OrderStatus::PAID => 'border-green-200 bg-green-100 text-green-800',
            OrderStatus::CANCELLED => 'border-pink-200 bg-pink-100 text-pink-800',
            OrderStatus::COMPLETED => 'border-purple-200 bg-purple-100 text-purple-800',
        };
    }

    public function getFormattedStatusAttribute(): string
    {
        return OrderStatus::values()[$this->status];
    }

    public function total(): int
    {
        return $this->items->sum('total');
    }

    public function canBeCancelled(): bool
    {
        if ($this->status === OrderStatus::COMPLETED || $this->status === OrderStatus::PAID) {
            return false;
        }

        return true;
    }

    public function isNotCancelled(): bool
    {
        if ($this->status === OrderStatus::CANCELLED) {
            return false;
        }

        return true;
    }

    public function isPending(): bool
    {
        return $this->status === OrderStatus::PENDING;
    }

    public function isRegister(): bool
    {
        return $this->status === OrderStatus::REGISTER;
    }

    public function isPaid(): bool
    {
        return $this->status === OrderStatus::PAID;
    }

    public function isCompleted(): bool
    {
        return $this->status === OrderStatus::COMPLETED;
    }

    public function fullPriceWithShipping(): int
    {
        return $this->total() + $this->shipping_total;
    }

    public function shippingAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'shipping_address_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model', User::class), 'user_id');
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    public function refund(): HasOne
    {
        return $this->hasOne(OrderRefund::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    protected function setDefaultOrderStatus(): void
    {
        $this->setRawAttributes(
            array_merge(
                $this->attributes,
                [
                    'status' => OrderStatus::PENDING,
                ]
            ),
            true
        );
    }
}
