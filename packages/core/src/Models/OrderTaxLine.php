<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Shopper\Core\Models\Contracts\OrderTaxLine as OrderTaxLineContract;

/**
 * @property-read int $id
 * @property-read string $code
 * @property-read string $name
 * @property-read float $rate
 * @property-read int $amount
 * @property-read string $taxable_type
 * @property-read int $taxable_id
 * @property-read ?int $tax_rate_id
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 * @property-read Model $taxable
 * @property-read ?TaxRate $taxRate
 */
class OrderTaxLine extends Model implements OrderTaxLineContract
{
    protected $guarded = [];

    public function getTable(): string
    {
        return shopper_table('order_tax_lines');
    }

    /**
     * @return MorphTo<Model, $this>
     */
    public function taxable(): MorphTo
    {
        return $this->morphTo();
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
