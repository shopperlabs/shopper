<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Shopper\Core\Database\Factories\BrandFactory;
use Shopper\Core\Models\Traits\HasMedia;
use Shopper\Core\Models\Traits\HasSlug;
use Spatie\MediaLibrary\HasMedia as SpatieHasMedia;

/**
 * @property-read int $id
 * @property-read string $name
 * @property-read string|null $slug
 * @property-read string|null $website
 * @property-read string|null $description
 * @property-read int $position
 * @property-read bool $is_enabled
 * @property-read string|null $seo_title
 * @property-read string|null $seo_description
 * @property-read array<string, mixed>|null $metadata
 */
class Brand extends Model implements SpatieHasMedia
{
    /** @use HasFactory<BrandFactory> */
    use HasFactory;

    use HasMedia;
    use HasSlug;

    protected $guarded = [];

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
        // @phpstan-ignore-next-line
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
