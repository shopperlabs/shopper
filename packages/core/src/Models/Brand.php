<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Shopper\Core\Database\Factories\BrandFactory;
use Shopper\Core\Models\Contracts\Brand as BrandContract;
use Shopper\Core\Models\Traits\HasMedia;
use Shopper\Core\Models\Traits\HasSlug;
use Shopper\Core\Traits\HasModelContract;
use Spatie\MediaLibrary\HasMedia as SpatieHasMedia;

/**
 * @property-read int $id
 * @property-read string $name
 * @property-read ?string $slug
 * @property-read ?string $website
 * @property-read ?string $description
 * @property-read int $position
 * @property-read bool $is_enabled
 * @property-read ?string $seo_title
 * @property-read ?string $seo_description
 * @property-read array<string, mixed>|null $metadata
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Product> $products
 */
class Brand extends Model implements BrandContract, SpatieHasMedia
{
    /** @use HasFactory<BrandFactory> */
    use HasFactory;

    use HasMedia;
    use HasModelContract;
    use HasSlug;

    protected $guarded = [];

    public static function configKey(): string
    {
        return 'brand';
    }

    public function getTable(): string
    {
        return shopper_table('brands');
    }

    public function updateStatus(bool $status = true): void
    {
        $this->update(['is_enabled' => $status]);
    }

    /**
     * @param  Builder<Brand>  $query
     * @return Builder<Brand>
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
        return $this->hasMany(config('shopper.models.product'));
    }

    protected static function newFactory(): BrandFactory
    {
        return BrandFactory::new();
    }

    protected function casts(): array
    {
        return [
            'is_enabled' => 'boolean',
            'metadata' => 'array',
        ];
    }
}
