<?php

declare(strict_types=1);

namespace Shopper\Cart\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Shopper\Core\Models\Contracts\CartLine as CartLineContract;
use Shopper\Core\Traits\HasModelContract;

/**
 * @property-read int $id
 * @property-read int $cart_id
 * @property-read string $purchasable_type
 * @property-read int $purchasable_id
 * @property-read int $quantity
 * @property-read float|int $unit_price_amount
 * @property-read ?array<string, mixed> $metadata
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 * @property-read Cart $cart
 * @property-read Model $purchasable
 * @property-read Collection<int, CartLineAdjustment> $adjustments
 * @property-read Collection<int, CartLineTaxLine> $taxLines
 */
class CartLine extends Model implements CartLineContract
{
    use HasModelContract;

    protected $guarded = [];

    public static function configuredClass(): string
    {
        return config('shopper.cart.models.cart_line', static::class);
    }

    public function getTable(): string
    {
        return shopper_table('cart_lines');
    }

    /**
     * @return BelongsTo<Cart, $this>
     */
    public function cart(): BelongsTo
    {
        return $this->belongsTo(config('shopper.cart.models.cart', Cart::class));
    }

    /**
     * @return MorphTo<Model, $this>
     */
    public function purchasable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return HasMany<CartLineAdjustment, $this>
     */
    public function adjustments(): HasMany
    {
        return $this->hasMany(CartLineAdjustment::class);
    }

    /**
     * @return HasMany<CartLineTaxLine, $this>
     */
    public function taxLines(): HasMany
    {
        return $this->hasMany(CartLineTaxLine::class);
    }

    protected function unitPriceAmount(): Attribute
    {
        return Attribute::make(
            get: fn (float|int $value): float|int => $value / 100,
            set: fn (float|int $value): int => (int) round($value * 100),
        );
    }

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
        ];
    }
}
