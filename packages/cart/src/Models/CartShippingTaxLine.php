<?php

declare(strict_types=1);

namespace Shopper\Cart\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Shopper\Core\Models\TaxRate;

/**
 * @property-read int $id
 * @property-read int $cart_id
 * @property-read string $code
 * @property-read string $name
 * @property-read float $rate
 * @property-read int $amount
 * @property-read ?int $tax_rate_id
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 * @property-read Cart $cart
 * @property-read ?TaxRate $taxRate
 */
class CartShippingTaxLine extends Model
{
    protected $guarded = [];

    public function getTable(): string
    {
        return shopper_table('cart_shipping_tax_lines');
    }

    /**
     * @return BelongsTo<Cart, $this>
     */
    public function cart(): BelongsTo
    {
        return $this->belongsTo(config('shopper.cart.models.cart', Cart::class));
    }

    /**
     * @return BelongsTo<TaxRate, $this>
     */
    public function taxRate(): BelongsTo
    {
        return $this->belongsTo(config('shopper.models.tax_rate'), 'tax_rate_id');
    }

    protected function casts(): array
    {
        return [
            'rate' => 'float',
        ];
    }
}
