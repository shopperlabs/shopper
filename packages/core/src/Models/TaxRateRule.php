<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Shopper\Core\Database\Factories\TaxRateRuleFactory;

/**
 * @property-read int $id
 * @property-read string $reference_type
 * @property-read string $reference_id
 * @property-read int $tax_rate_id
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 * @property-read TaxRate $taxRate
 */
class TaxRateRule extends Model
{
    /** @use HasFactory<TaxRateRuleFactory> */
    use HasFactory;

    protected $guarded = [];

    public function getTable(): string
    {
        return shopper_table('tax_rate_rules');
    }

    /**
     * @return BelongsTo<TaxRate, $this>
     */
    public function taxRate(): BelongsTo
    {
        return $this->belongsTo(TaxRate::class, 'tax_rate_id');
    }

    protected static function newFactory(): TaxRateRuleFactory
    {
        return TaxRateRuleFactory::new();
    }
}
