<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Shopper\Core\Database\Factories\OrderRefundFactory;
use Shopper\Core\Enum\OrderRefundStatus;

/**
 * @property-read int $id
 * @property string|null $refund_reason
 * @property int|null $refund_amount
 * @property OrderRefundStatus $status
 * @property string|null $notes
 * @property string $currency
 * @property int $order_id
 * @property int|null $user_id
 * @property-read Order $order
 * @property-read User|null $customer
 */
class OrderRefund extends Model
{
    use HasFactory;

    protected $guarded = [];

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

    protected static function newFactory(): OrderRefundFactory
    {
        return OrderRefundFactory::new();
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model', User::class), 'user_id');
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
                ['status' => OrderRefundStatus::Pending->value]
            ),
            true
        );
    }
}
