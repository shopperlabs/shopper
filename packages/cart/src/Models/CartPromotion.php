<?php

declare(strict_types=1);

namespace Shopper\Cart\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Shopper\Cart\Database\Factories\CartPromotionFactory;
use Shopper\Core\Enum\PromotionSource;
use Shopper\Core\Models\Discount;

/**
 * @property-read int $id
 * @property-read int $cart_id
 * @property-read ?int $discount_id
 * @property-read PromotionSource $source
 * @property-read ?string $code
 * @property-read int $computed_amount
 * @property-read int $sequence
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 * @property-read Cart $cart
 * @property-read ?Discount $discount
 */
class CartPromotion extends Model
{
    /** @use HasFactory<CartPromotionFactory> */
    use HasFactory;

    protected $guarded = [];

    public function getTable(): string
    {
        return shopper_table('cart_promotions');
    }

    /**
     * @return BelongsTo<Cart, $this>
     */
    public function cart(): BelongsTo
    {
        return $this->belongsTo(config('shopper.cart.models.cart', Cart::class), 'cart_id');
    }

    /**
     * @return BelongsTo<Discount, $this>
     */
    public function discount(): BelongsTo
    {
        return $this->belongsTo(Discount::class, 'discount_id');
    }

    protected static function newFactory(): CartPromotionFactory
    {
        return CartPromotionFactory::new();
    }

    protected function casts(): array
    {
        return [
            'source' => PromotionSource::class,
            'computed_amount' => 'integer',
            'sequence' => 'integer',
        ];
    }
}
