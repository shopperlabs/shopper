<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Shopper\Core\Database\Factories\OrderItemFactory;
use Shopper\Core\Models\Contracts\OrderItem as OrderItemContract;

/**
 * @property-read int $id
 * @property-read string $name
 * @property-read int $quantity
 * @property-read int $unit_price_amount
 * @property-read int $total
 * @property-read string $sku
 * @property-read int $product_id
 * @property-read string $product_type
 * @property-read int $order_id
 * @property-read Contracts\Order $order
 */
class OrderItem extends Model implements OrderItemContract
{
    /** @use HasFactory<OrderItemFactory> */
    use HasFactory;

    protected $guarded = [];

    public function getTable(): string
    {
        return shopper_table('order_items');
    }

    /**
     * @return MorphTo<Model, $this>
     */
    public function product(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return BelongsTo<Order, $this>
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    protected static function newFactory(): OrderItemFactory
    {
        return OrderItemFactory::new();
    }

    protected function total(): Attribute
    {
        return Attribute::make(
            get: fn (): int => $this->unit_price_amount * $this->quantity
        );
    }
}
