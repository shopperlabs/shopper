<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Shopper\Core\Database\Factories\TaxRateFactory;
use Shopper\Core\Models\Contracts\TaxRate as TaxRateContract;
use Shopper\Core\Traits\HasModelContract;

/**
 * @property-read int $id
 * @property-read string $name
 * @property-read ?string $code
 * @property-read float $rate
 * @property-read bool $is_default
 * @property-read bool $is_combinable
 * @property-read int $tax_zone_id
 * @property-read array<string, mixed>|null $metadata
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 * @property-read TaxZone $taxZone
 * @property-read EloquentCollection<int, TaxRateRule> $rules
 */
class TaxRate extends Model implements TaxRateContract
{
    /** @use HasFactory<TaxRateFactory> */
    use HasFactory;

    use HasModelContract;

    protected $guarded = [];

    public static function configKey(): string
    {
        return 'tax_rate';
    }

    public function getTable(): string
    {
        return shopper_table('tax_rates');
    }

    /**
     * @return BelongsTo<TaxZone, $this>
     */
    public function taxZone(): BelongsTo
    {
        return $this->belongsTo(config('shopper.models.tax_zone'), 'tax_zone_id');
    }

    /**
     * @return HasMany<TaxRateRule, $this>
     */
    public function rules(): HasMany
    {
        return $this->hasMany(TaxRateRule::class, 'tax_rate_id');
    }

    protected static function newFactory(): TaxRateFactory
    {
        return TaxRateFactory::new();
    }

    protected function casts(): array
    {
        return [
            'rate' => 'float',
            'is_default' => 'boolean',
            'is_combinable' => 'boolean',
            'metadata' => 'array',
        ];
    }
}
