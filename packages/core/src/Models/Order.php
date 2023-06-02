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

class Order extends Model
{
    use HasFactory;
    use HasPrice;
    use SoftDeletes;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<string>|bool
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'status' => OrderStatus::class,
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
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

    public function canBeCancelled(): bool
    {
        if ($this->status === OrderStatus::COMPLETED || $this->status === OrderStatus::PAID) {
            return false;
        }

        return true;
    }

    public function isNotCancelled(): bool
    {
        if ($this->status === OrderStatus::CANCELLED) {
            return false;
        }

        return true;
    }

    public function isPending(): bool
    {
        return $this->status === OrderStatus::PENDING;
    }

    public function isRegister(): bool
    {
        return $this->status === OrderStatus::REGISTER;
    }

    public function isPaid(): bool
    {
        return $this->status === OrderStatus::PAID;
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
                [
                    'status' => OrderStatus::PENDING,
                ]
            ),
            true
        );
    }
}
