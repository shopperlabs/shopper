<?php

namespace Shopper\Framework\Models\Shop\Order;

use Illuminate\Database\Eloquent\Model;
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
    public function getTable()
    {
        return shopper_table('orders');
    }

    /**
     * Return Total order price without shipping amount.
     *
     * @return bool|string
     */
    public function getTotalAttribute()
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
    public function getFormattedStatusAttribute()
    {
        return OrderStatus::values()[$this->status];
    }

    /**
     * Return total order.
     *
     * @return mixed
     */
    public function total()
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
     * Return total order with shipping.
     *
     * @return int
     */
    public function fullPriceWithShipping(): int
    {
        return $this->items->sum('total') + $this->shipping_total;
    }

    /**
     * Get the user Shipping Address for the order.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shippingAddress()
    {
        return $this->belongsTo(Address::class, 'shipping_address_id');
    }

    /**
     * Return the associate User for this order.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(config('auth.providers.users.model', User::class), 'user_id');
    }

    /**
     * Return the associate Payment method for this order if exist.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    /**
     * Return the refund instance of the order.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function refund()
    {
        return $this->hasOne(OrderRefund::class);
    }

    /**
     * Get all items of the order.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Set a default value to an order.
     *
     * @return void
     */
    protected function setDefaultOrderStatus()
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
