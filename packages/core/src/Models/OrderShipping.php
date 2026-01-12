<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Shopper\Core\Database\Factories\OrderShippingFactory;
use Shopper\Core\Models\Contracts\OrderShipping as OrderShippingContract;

/**
 * @property-read int $id
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
        return $this->belongsTo(Order::class, 'order_id');
    }

    /**
     * @return BelongsTo<Carrier, $this>
     */
    public function carrier(): BelongsTo
    {
        return $this->belongsTo(Carrier::class, 'carrier_id');
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
