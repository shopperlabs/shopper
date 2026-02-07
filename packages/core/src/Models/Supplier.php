<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Shopper\Core\Database\Factories\SupplierFactory;
use Shopper\Core\Models\Contracts\Supplier as SupplierContract;
use Shopper\Core\Models\Traits\HasSlug;
use Shopper\Core\Traits\HasModelContract;

/**
 * @property-read int $id
 * @property-read string $name
 * @property-read ?string $slug
 * @property-read ?string $email
 * @property-read ?string $phone
 * @property-read ?string $contact_name
 * @property-read ?string $website
 * @property-read ?string $description
 * @property-read ?string $notes
 * @property-read bool $is_enabled
 * @property-read array<string, mixed>|null $metadata
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 */
class Supplier extends Model implements SupplierContract
{
    /** @use HasFactory<SupplierFactory> */
    use HasFactory;

    use HasModelContract;
    use HasSlug;

    protected $guarded = [];

    public static function configKey(): string
    {
        return 'supplier';
    }

    public function getTable(): string
    {
        return shopper_table('suppliers');
    }

    public function updateStatus(bool $status = true): void
    {
        $this->update(['is_enabled' => $status]);
    }

    /**
     * @param  Builder<Supplier>  $query
     * @return Builder<Supplier>
     */
    public function scopeEnabled(Builder $query): Builder
    {
        return $query->where('is_enabled', true);
    }

    /**
     * @return HasMany<Product, $this>
     */
    public function products(): HasMany
    {
        // @phpstan-ignore-next-line
        return $this->hasMany(config('shopper.models.product'), 'supplier_id');
    }

    protected static function newFactory(): SupplierFactory
    {
        return SupplierFactory::new();
    }

    protected function casts(): array
    {
        return [
            'is_enabled' => 'boolean',
            'metadata' => 'array',
        ];
    }
}
