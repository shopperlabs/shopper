<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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
 * @property string $number
 * @property int $price_amount
 * @property string $notes
 * @property string $currency
 * @property string $shipping_method
 * @property int | null $user_id
 * @property \Illuminate\Database\Eloquent\Collection|\Shopper\Core\Models\OrderItem[] $items
 * @property OrderStatus $status
 * @property int $shipping_total
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
        'total_amount',
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

    public function totalAmount(): Attribute
    {
        return Attribute::get(
            fn () => $this->formattedPrice($this->total())
        );
    }

    public function total(): int
    {
        return $this->items->sum('total');
    }

    public function canBeCancelled(): bool
    {
        return ! ($this->status === OrderStatus::Completed || $this->status === OrderStatus::Paid);
    }

    public function isNotCancelled(): bool
    {
        return ! ($this->status === OrderStatus::Cancelled);
    }

    public function isPending(): bool
    {
        return $this->status === OrderStatus::Pending;
    }

    public function isRegister(): bool
    {
        return $this->status === OrderStatus::Register;
    }

    public function isPaid(): bool
    {
        return $this->status === OrderStatus::Paid;
    }

    public function isCompleted(): bool
    {
        return $this->status === OrderStatus::COMPLETED;
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
                ['status' => OrderStatus::Pending->value]
            ),
            true
        );
    }
}
