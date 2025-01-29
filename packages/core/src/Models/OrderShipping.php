<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Shopper\Core\Database\Factories\OrderShippingFactory;

/**
 * @property-read int $id
 * @property Carbon $shipped_at
 * @property Carbon|null $received_at
 * @property Carbon|null $returned_at
 * @property string|null $tracking_number
 * @property string|null $tracking_url
 * @property array|null $voucher
 * @property int $order_id
 * @property int | null $carrier_id
 * @property-read Order $order
 * @property-read Carrier | null $carrier
 */
class OrderShipping extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'shipped_at' => 'datetime',
        'received_at' => 'datetime',
        'returned_at' => 'datetime',
        'voucher' => 'json',
    ];

    public function getTable(): string
    {
        return shopper_table('order_shipping');
    }

    protected static function newFactory(): OrderShippingFactory
    {
        return OrderShippingFactory::new();
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function carrier(): BelongsTo
    {
        return $this->belongsTo(Carrier::class, 'carrier_id');
    }
}
