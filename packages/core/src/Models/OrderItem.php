<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property-read int $id
 * @property int $quantity
 * @property int $unit_price_amount
 */
class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sku',
        'quantity',
        'unit_price_amount',
        'product_id',
        'product_type',
        'order_id',
    ];

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
