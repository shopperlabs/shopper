<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Shopper\Core\Database\Factories\OrderItemFactory;
use Shopper\Core\Enum\FulfillmentStatus;
use Shopper\Core\Models\Contracts\OrderItem as OrderItemContract;

/**
 * @property-read int $id
 * @property-read string $name
 * @property-read int $quantity
 * @property-read float|int|null $unit_price_amount
 * @property-read float|int|null $total
 * @property-read string $sku
 * @property-read int $product_id
 * @property-read string $product_type
 * @property-read int $tax_amount
 * @property-read int $discount_amount
 * @property-read int $order_id
 * @property-read ?int $order_shipping_id
 * @property-read ?FulfillmentStatus $fulfillment_status
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 * @property-read Contracts\Order $order
 * @property-read ?OrderShipping $shipment
 * @property-read \Illuminate\Database\Eloquent\Collection<int, OrderTaxLine> $taxLines
 * @property-read Model $product
 */
class OrderItem extends Model implements OrderItemContract
{
    /** @use HasFactory<OrderItemFactory> */
    use HasFactory;

    protected $guarded = [];

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function __construct(array $attributes = [])
    {
        if (! isset($attributes['fulfillment_status'])) {
            $this->setRawAttributes(
                array_merge($this->attributes, [
                    'fulfillment_status' => FulfillmentStatus::Pending,
                ]),
                true
            );
        }

        parent::__construct($attributes);
    }

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
        return $this->belongsTo(config('shopper.models.order'), 'order_id');
    }

    /**
     * @return MorphMany<OrderTaxLine, $this>
     */
    public function taxLines(): MorphMany
    {
        return $this->morphMany(OrderTaxLine::class, 'taxable');
    }

    /**
     * @return BelongsTo<OrderShipping, $this>
     */
    public function shipment(): BelongsTo
    {
        return $this->belongsTo(OrderShipping::class, 'order_shipping_id');
    }

    protected static function newFactory(): OrderItemFactory
    {
        return OrderItemFactory::new();
    }

    protected function casts(): array
    {
        return [
            'fulfillment_status' => FulfillmentStatus::class,
        ];
    }

    protected function total(): Attribute
    {
        return Attribute::make(
            get: fn (): int|float => ($this->unit_price_amount * $this->quantity) - $this->discount_amount
        );
    }
}
