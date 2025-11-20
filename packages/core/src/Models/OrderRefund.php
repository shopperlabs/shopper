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
 * @property-read string|null $refund_reason
 * @property-read int|null $refund_amount
 * @property-read OrderRefundStatus $status
 * @property-read string|null $notes
 * @property-read string $currency
 * @property-read int $order_id
 * @property-read int|null $user_id
 * @property-read Order $order
 * @property-read User|null $customer
 */
class OrderRefund extends Model
{
    /** @use HasFactory<OrderRefundFactory> */
    use HasFactory;

    protected $guarded = [];

    /**
     * @param  array<string, mixed>  $attributes
     */
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

    /**
     * @return BelongsTo<User, $this>
     */
    public function customer(): BelongsTo
    {
        // @phpstan-ignore-next-line
        return $this->belongsTo(config('auth.providers.users.model', User::class), 'user_id');
    }

    /**
     * @return BelongsTo<Order, $this>
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    protected static function newFactory(): OrderRefundFactory
    {
        return OrderRefundFactory::new();
    }

    protected function casts(): array
    {
        return [
            'status' => OrderRefundStatus::class,
        ];
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
