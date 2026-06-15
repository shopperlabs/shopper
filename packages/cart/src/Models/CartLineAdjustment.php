<?php

declare(strict_types=1);

namespace Shopper\Cart\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Shopper\Cart\Database\Factories\CartLineAdjustmentFactory;
use Shopper\Core\Models\Discount;

/**
 * @property-read int $id
 * @property-read int $cart_line_id
 * @property-read ?int $cart_promotion_id
 * @property-read float|int $amount
 * @property-read ?string $code
 * @property-read ?int $discount_id
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 * @property-read CartLine $cartLine
 * @property-read ?CartPromotion $cartPromotion
 * @property-read ?Discount $discount
 */
class CartLineAdjustment extends Model
{
    /** @use HasFactory<CartLineAdjustmentFactory> */
    use HasFactory;

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
        return $this->belongsTo(config('shopper.cart.models.cart_line', CartLine::class));
    }

    /**
     * @return BelongsTo<CartPromotion, $this>
     */
    public function cartPromotion(): BelongsTo
    {
        return $this->belongsTo(CartPromotion::class, 'cart_promotion_id');
    }

    /**
     * @return BelongsTo<Discount, $this>
     */
    public function discount(): BelongsTo
    {
        return $this->belongsTo(config('shopper.models.discount'), 'discount_id');
    }

    protected static function newFactory(): CartLineAdjustmentFactory
    {
        return CartLineAdjustmentFactory::new();
    }
}
