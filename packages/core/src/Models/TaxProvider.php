<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Shopper\Core\Database\Factories\TaxProviderFactory;

/**
 * @property-read int $id
 * @property-read string $identifier
 * @property-read bool $is_enabled
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 * @property-read EloquentCollection<int, TaxZone> $taxZones
 */
class TaxProvider extends Model
{
    /** @use HasFactory<TaxProviderFactory> */
    use HasFactory;

    protected $guarded = [];

    public function getTable(): string
    {
        return shopper_table('tax_providers');
    }

    public function isEnabled(): bool
    {
        return $this->is_enabled;
    }

    /**
     * @return HasMany<TaxZone, $this>
     */
    public function taxZones(): HasMany
    {
        return $this->hasMany(TaxZone::class, 'provider_id');
    }

    protected static function newFactory(): TaxProviderFactory
    {
        return TaxProviderFactory::new();
    }

    protected function casts(): array
    {
        return [
            'is_enabled' => 'boolean',
        ];
    }
}
