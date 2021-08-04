<?php

namespace Shopper\Framework\Models\Shop\Order;

use Illuminate\Database\Eloquent\Model;
use Shopper\Framework\Models\User\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderRefund extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Create a new Eloquent model instance.
     */
    public function __construct(array $attributes = [])
    {
        // Set default status in case there was none given
        if (! isset($attributes['status'])) {
            $this->setDefaultOrderRefundStatus();
        }

        parent::__construct($attributes);
    }

    /**
     * Get the table associated with the model.
     */
    public function getTable(): string
    {
        return shopper_table('order_refunds');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    protected function setDefaultOrderRefundStatus(): void
    {
        $this->setRawAttributes(
            array_merge(
                $this->attributes,
                [
                    'status' => OrderRefundStatus::PENDING,
                ]
            ),
            true
        );
    }
}
