<?php

namespace Shopper\Framework\Models\Shop\Order;

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
    use HasPrice, SoftDeletes;

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

    /**
     * Create a new Eloquent model instance.
     *
     * @param  array  $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        // Set default status in case there was none given
        if (! isset($attributes['status'])) {
            $this->setDefaultOrderStatus();
        }

        parent::__construct($attributes);
    }

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable(): string
    {
        return shopper_table('orders');
    }

    /**
     * Return Total order price without shipping amount.
     *
     */
    public function getTotalAttribute(): string
    {
        return $this->formattedPrice($this->total());
    }

    /**
     * Return status style classes.
     *
     * @return string
     */
    public function getStatusClassesAttribute(): string
    {
        switch ($this->status) {
            case OrderStatus::PENDING:
                return 'border-yellow-200 bg-yellow-100 text-yellow-800';
            case OrderStatus::REGISTER:
                return 'border-blue-200 bg-blue-100 text-blue-800';
            case OrderStatus::PAID:
                return 'border-green-200 bg-green-100 text-green-800';
            case OrderStatus::CANCELLED:
                return 'border-red-200 bg-red-100 text-red-800';
        }
    }

    /**
     * Return the correct order status formatted.
     *
     * @return mixed
     */
    public function getFormattedStatusAttribute(): string
    {
        return OrderStatus::values()[$this->status];
    }

    public function total(): int
    {
        return $this->items->sum('total');
    }

    /**
     * Determine if an order can be cancelled.
     *
     * @return bool
     */
    public function canBeCancelled(): bool
    {
        if ($this->status === OrderStatus::COMPLETED || $this->status === OrderStatus::PAID) {
            return false;
        }

        return true;
    }

    /**
     * Determine if an order is not cancelled.
     *
     * @return bool
     */
    public function isNotCancelled(): bool
    {
        if ($this->status === OrderStatus::CANCELLED) {
            return false;
        }

        return true;
    }

    /**
     * Determine if on order is in pending status.
     *
     * @return bool
     */
    public function isPending(): bool
    {
        return $this->status === OrderStatus::PENDING;
    }

    /**
     * Determine if on order is in register status.
     *
     * @return bool
     */
    public function isRegister(): bool
    {
        return $this->status === OrderStatus::REGISTER;
    }

    /**
     * Return total order with shipping.
     *
     * @return int
     */
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
