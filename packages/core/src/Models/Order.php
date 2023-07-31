<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Shopper\Core\Enum\OrderStatus;
use Shopper\Core\Traits\HasPrice;

/**
 * @property-read int $id
 * @property-read \App\Models\User $customer
 * @property-read \Illuminate\Database\Eloquent\Collection|\Shopper\Core\Models\OrderItem[] $items
 * @property-read string $status
 * @property-read int $shipping_total
 */
class Order extends Model
{
    use HasFactory;
    use HasPrice;
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'status' => OrderStatus::class,
    ];

    protected $appends = [
        'total',
    ];

    public function __construct(array $attributes = [])
    {
        if (! isset($attributes['status'])) {
            $this->setDefaultOrderStatus();
        }

        parent::__construct($attributes);
    }

    public function getTable(): string
    {
        return shopper_table('orders');
    }

    public function getTotalAttribute(): string
    {
        return $this->formattedPrice($this->total());
    }

    public function total(): int
    {
        return $this->items->sum('total');
    }

    public static function statusValues(): array
    {
        return [
            OrderStatus::PENDING->value => __('shopper::status.pending'),
            OrderStatus::REGISTER->value => __('shopper::status.registered'),
            OrderStatus::COMPLETED->value => __('shopper::status.completed'),
            OrderStatus::CANCELLED->value => __('shopper::status.cancelled'),
            OrderStatus::PAID->value => __('shopper::status.paid'),
        ];
    }

    public function canBeCancelled(): bool
    {
        return ! ($this->status === OrderStatus::COMPLETED->value || $this->status === OrderStatus::PAID->value);
    }

    public function isNotCancelled(): bool
    {
        return ! ($this->status === OrderStatus::CANCELLED->value);
    }

    public function isPending(): bool
    {
        return $this->status === OrderStatus::PENDING->value;
    }

    public function isRegister(): bool
    {
        return $this->status === OrderStatus::REGISTER->value;
    }

    public function isPaid(): bool
    {
        return $this->status === OrderStatus::PAID->value;
    }

    public function isCompleted(): bool
    {
        return $this->status === OrderStatus::COMPLETED->value;
    }

    public function fullPriceWithShipping(): int
    {
        return $this->total() + $this->shipping_total;
    }

    public function shippingAddress(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'shipping_address_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model', User::class), 'user_id');
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    public function refund(): HasOne
    {
        return $this->hasOne(OrderRefund::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    protected function setDefaultOrderStatus(): void
    {
        $this->setRawAttributes(
            array_merge(
                $this->attributes,
                ['status' => OrderStatus::PENDING->value]
            ),
            true
        );
    }
}
