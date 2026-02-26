<?php

declare(strict_types=1);

namespace Shopper\Cart\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Shopper\Core\Models\Discount;

/**
 * @property-read int $id
 * @property-read int $cart_line_id
 * @property-read float|int $amount
 * @property-read ?string $code
 * @property-read ?int $discount_id
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 * @property-read CartLine $cartLine
 * @property-read ?Discount $discount
 */
class CartLineAdjustment extends Model
{
    protected $guarded = [];

    public function getTable(): string
    {
        return shopper_table('cart_line_adjustments');
    }

    /**
     * @return BelongsTo<CartLine, $this>
     */
    public function cartLine(): BelongsTo
    {
        return $this->belongsTo(CartLine::class);
    }

    /**
     * @return BelongsTo<Discount, $this>
     */
    public function discount(): BelongsTo
    {
        return $this->belongsTo(config('shopper.models.discount'), 'discount_id');
    }

    protected function amount(): Attribute
    {
        return Attribute::make(
            get: fn (float|int $value): float|int => $value / 100,
            set: fn (float|int $value): int => (int) round($value * 100),
        );
    }
}
