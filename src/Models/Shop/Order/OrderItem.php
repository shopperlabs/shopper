<?php

declare(strict_types=1);

namespace Shopper\Framework\Models\Shop\Order;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class OrderItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'sku',
        'quantity',
        'unit_price_amount',
        'product_id',
        'product_type',
        'order_id',
    ];

    /**
     * Get the table associated with the model.
     */
    public function getTable(): string
    {
        return shopper_table('order_items');
    }

    public function total(): int
    {
        return $this->unit_price_amount * $this->quantity;
    }

    public function getTotalAttribute(): int
    {
        return $this->total();
    }

    public function product(): MorphTo
    {
        return $this->morphTo();
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
