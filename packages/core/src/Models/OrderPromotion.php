<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Shopper\Core\Enum\DiscountType;

/**
 * @property-read int $id
 * @property-read int $order_id
 * @property-read ?int $discount_id
 * @property-read ?string $code
 * @property-read DiscountType $type
 * @property-read int $value_at_apply
 * @property-read int $amount
 * @property-read string $currency_code
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 * @property-read Order $order
 * @property-read ?Discount $discount
 */
class OrderPromotion extends Model
{
    protected $guarded = [];

    public function getTable(): string
    {
        return shopper_table('order_promotions');
    }

    /**
     * @return BelongsTo<Order, $this>
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(config('shopper.models.order', Order::class), 'order_id');
    }

    /**
     * @return BelongsTo<Discount, $this>
     */
    public function discount(): BelongsTo
    {
        return $this->belongsTo(Discount::class, 'discount_id');
    }

    protected function casts(): array
    {
        return [
            'type' => DiscountType::class,
            'value_at_apply' => 'integer',
            'amount' => 'integer',
        ];
    }
}
