<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderShipping extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'shipped_at' => 'datetime',
        'received_at' => 'datetime',
        'returned_at' => 'datetime',
    ];

    public function getTable(): string
    {
        return shopper_table('order_shipping');
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
