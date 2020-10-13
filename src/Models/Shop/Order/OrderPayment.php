<?php

namespace Shopper\Framework\Models\Shop\Order;

use Illuminate\Database\Eloquent\Model;
use Shopper\Framework\Models\User\User;
use Shopper\Framework\Models\Shop\Order;

class OrderPayment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Create a new Eloquent model instance.
     *
     * @param  array  $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        // Set default status in case there was none given
        if (!isset($attributes['status'])) {
            $this->setDefaultOrderPaymentStatus();
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
        return shopper_table('order_payments');
    }

    /**
     * Return the associate User for this order payment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Return the associate Order for this order payment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }


    /**
     * Set a default value to an order.
     *
     * @return void
     */
    protected function setDefaultOrderPaymentStatus()
    {
        $this->setRawAttributes(
            array_merge(
                $this->attributes,
                [
                    'status' => OrderPaymentStatus::PENDING,
                ]
            ),
            true
        );
    }
}
