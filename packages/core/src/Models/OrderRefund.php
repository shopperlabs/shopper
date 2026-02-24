<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Shopper\Core\Database\Factories\OrderRefundFactory;
use Shopper\Core\Enum\OrderRefundStatus;
use Shopper\Core\Models\Contracts\OrderRefund as OrderRefundContract;
use Shopper\Core\Models\Contracts\ShopperUser;

/**
 * @property-read int $id
 * @property-read ?string $refund_reason
 * @property-read ?int $refund_amount
 * @property-read OrderRefundStatus $status
 * @property-read ?string $notes
 * @property-read string $currency
 * @property-read int $order_id
 * @property-read ?int $user_id
 * @property-read Order $order
 * @property-read array<string, mixed>|null $metadata
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 * @property-read Model|null $customer
 */
class OrderRefund extends Model implements OrderRefundContract
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

    public function setDefaultOrderRefundStatus(): void
    {
        $this->setRawAttributes(
            array_merge(
                $this->attributes,
                ['status' => OrderRefundStatus::Pending]
            ),
            true
        );
    }

    public function getTable(): string
    {
        return shopper_table('order_refunds');
    }

    /**
     * @return BelongsTo<Model&ShopperUser, $this>
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'user_id');
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
}
