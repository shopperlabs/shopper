<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Shopper\Core\Database\Factories\OrderShippingEventFactory;
use Shopper\Core\Enum\ShipmentEventSource;
use Shopper\Core\Enum\ShipmentStatus;
use Shopper\Core\Models\Traits\HasPublicId;

/**
 * @property-read int $id
 * @property-read ?string $public_id
 * @property-read ShipmentStatus $status
 * @property-read ?string $external_id
 * @property-read ShipmentEventSource $source
 * @property-read ?int $causer_id
 * @property-read ?string $description
 * @property-read ?string $location
 * @property-read ?float $latitude
 * @property-read ?float $longitude
 * @property-read array<string, mixed>|null $metadata
 * @property-read CarbonInterface $occurred_at
 * @property-read CarbonInterface $created_at
 * @property-read int $order_shipping_id
 * @property-read OrderShipping $shipment
 */
class OrderShippingEvent extends Model
{
    /** @use HasFactory<OrderShippingEventFactory> */
    use HasFactory;

    use HasPublicId;

    protected $guarded = [];

    public function getTable(): string
    {
        return shopper_table('order_shipping_events');
    }

    /**
     * @return BelongsTo<OrderShipping, $this>
     */
    public function shipment(): BelongsTo
    {
        return $this->belongsTo(OrderShipping::class, 'order_shipping_id');
    }

    protected static function newFactory(): OrderShippingEventFactory
    {
        return OrderShippingEventFactory::new();
    }

    protected function casts(): array
    {
        return [
            'status' => ShipmentStatus::class,
            'source' => ShipmentEventSource::class,
            'occurred_at' => 'datetime',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'metadata' => 'json',
        ];
    }
}
