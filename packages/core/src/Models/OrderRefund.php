<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Shopper\Core\Enum\OrderRefundStatus;

class OrderRefund extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'status' => OrderRefundStatus::class,
    ];

    public function __construct(array $attributes = [])
    {
        if (! isset($attributes['status'])) {
            $this->setDefaultOrderRefundStatus();
        }

        parent::__construct($attributes);
    }

    public function getTable(): string
    {
        return shopper_table('order_refunds');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    protected function setDefaultOrderRefundStatus(): void
    {
        $this->setRawAttributes(
            array_merge(
                $this->attributes,
                ['status' => OrderRefundStatus::PENDING]
            ),
            true
        );
    }
}
