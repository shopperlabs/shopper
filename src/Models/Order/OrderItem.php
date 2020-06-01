<?php

namespace Shopper\Framework\Models\Order;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'sku',
        'quantity',
        'price',
        'product_id',
        'product_type',
        'order_id',
    ];

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
        return shopper_table('order_items');
    }

    /**
     * Return total item price.
     *
     * @return float|int
     */
    public function total()
    {
        return $this->price * $this->quantity;
    }

    /**
     * Property accessor alias to the total() method
     *
     * @return float
     */
    public function getTotalAttribute()
    {
        return $this->total();
    }

    /**
     * Get all of the owning product models.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function product()
    {
        return $this->morphTo();
    }

    /**
     * Get order of the current item.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
