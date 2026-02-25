<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Shopper\Core\Database\Factories\OrderShippingFactory;
use Shopper\Core\Enum\ShipmentStatus;
use Shopper\Core\Models\Contracts\OrderShipping as OrderShippingContract;
use Shopper\Core\Traits\HasFulfillmentTransitions;

/**
 * @property-read int $id
 * @property-read ?ShipmentStatus $status
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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, OrderShippingEvent> $events
 * @property-read \Illuminate\Database\Eloquent\Collection<int, OrderTaxLine> $taxLines
 */
class OrderShipping extends Model implements OrderShippingContract
{
    /** @use HasFactory<OrderShippingFactory> */
    use HasFactory;

    use HasFulfillmentTransitions;

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

    /**
     * @return HasMany<OrderShippingEvent, $this>
     */
    public function events(): HasMany
    {
        return $this->hasMany(OrderShippingEvent::class, 'order_shipping_id');
    }

    /**
     * @return MorphMany<OrderTaxLine, $this>
     */
    public function taxLines(): MorphMany
    {
        return $this->morphMany(OrderTaxLine::class, 'taxable');
    }

    protected static function newFactory(): OrderShippingFactory
    {
        return OrderShippingFactory::new();
    }

    protected function casts(): array
    {
        return [
            'status' => ShipmentStatus::class,
            'shipped_at' => 'datetime',
            'received_at' => 'datetime',
            'returned_at' => 'datetime',
            'voucher' => 'json',
        ];
    }
}
