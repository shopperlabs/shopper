<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Shopper\Core\Database\Factories\OrderShippingFactory;
use Shopper\Core\Models\Contracts\OrderShipping as OrderShippingContract;

/**
 * @property-read int $id
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 * @property-read CarbonInterface $shipped_at
 * @property-read ?CarbonInterface $received_at
 * @property-read ?CarbonInterface $returned_at
 * @property-read ?string $tracking_number
 * @property-read ?string $tracking_url
 * @property-read array<string, mixed>|null $voucher
 * @property-read int $order_id
 * @property-read ?int $carrier_id
 * @property-read Order $order
 * @property-read ?Carrier $carrier
 * @property-read \Illuminate\Database\Eloquent\Collection<int, OrderItem> $items
 */
class OrderShipping extends Model implements OrderShippingContract
{
    /** @use HasFactory<OrderShippingFactory> */
    use HasFactory;

    protected $guarded = [];

    public function getTable(): string
    {
        return shopper_table('order_shipping');
    }

    /**
     * @return BelongsTo<Order, $this>
     */
    public function order(): BelongsTo
    {
        // @phpstan-ignore-next-line
        return $this->belongsTo(config('shopper.models.order'), 'order_id');
    }

    /**
     * @return BelongsTo<Carrier, $this>
     */
    public function carrier(): BelongsTo
    {
        return $this->belongsTo(Carrier::class, 'carrier_id');
    }

    /**
     * @return HasMany<OrderItem, $this>
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_shipping_id');
    }

    protected static function newFactory(): OrderShippingFactory
    {
        return OrderShippingFactory::new();
    }

    protected function casts(): array
    {
        return [
            'shipped_at' => 'datetime',
            'received_at' => 'datetime',
            'returned_at' => 'datetime',
            'voucher' => 'json',
        ];
    }
}
