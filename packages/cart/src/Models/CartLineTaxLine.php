<?php

declare(strict_types=1);

namespace Shopper\Cart\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Shopper\Cart\Database\Factories\CartLineTaxLineFactory;
use Shopper\Core\Models\TaxRate;

/**
 * @property-read int $id
 * @property-read int $cart_line_id
 * @property-read string $code
 * @property-read string $name
 * @property-read float $rate
 * @property-read float|int $amount
 * @property-read ?int $tax_rate_id
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 * @property-read CartLine $cartLine
 * @property-read ?TaxRate $taxRate
 */
class CartLineTaxLine extends Model
{
    /** @use HasFactory<CartLineTaxLineFactory> */
    use HasFactory;

    protected $guarded = [];

    public function getTable(): string
    {
        return shopper_table('cart_line_tax_lines');
    }

    /**
     * @return BelongsTo<CartLine, $this>
     */
    public function cartLine(): BelongsTo
    {
        return $this->belongsTo(config('shopper.cart.models.cart_line', CartLine::class));
    }

    /**
     * @return BelongsTo<TaxRate, $this>
     */
    public function taxRate(): BelongsTo
    {
        return $this->belongsTo(config('shopper.models.tax_rate'), 'tax_rate_id');
    }

    protected static function newFactory(): CartLineTaxLineFactory
    {
        return CartLineTaxLineFactory::new();
    }

    protected function casts(): array
    {
        return [
            'rate' => 'float',
        ];
    }
}
