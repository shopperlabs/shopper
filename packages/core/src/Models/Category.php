<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Shopper\Core\Database\Factories\CategoryFactory;
use Shopper\Core\Models\Contracts\Category as CategoryContract;
use Shopper\Core\Models\Traits\HasMedia;
use Shopper\Core\Models\Traits\HasSlug;
use Shopper\Core\Traits\HasModelContract;
use Spatie\MediaLibrary\HasMedia as SpatieHasMedia;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;
use Staudenmeir\LaravelAdjacencyList\Eloquent\Relations\HasManyOfDescendants;

/**
 * @property-read int $id
 * @property-read string $name
 * @property-read string $slug
 * @property-read ?string $description
 * @property-read bool $is_enabled
 * @property-read int $position
 * @property-read ?string $seo_title
 * @property-read ?string $seo_description
 * @property-read ?int $parent_id
 * @property-read array<string, mixed>|null $metadata
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 * @property-read ?static $parent
 * @property-read Collection<int, Product> $products
 */
class Category extends Model implements CategoryContract, SpatieHasMedia
{
    /** @use HasFactory<CategoryFactory> */
    use HasFactory;

    use HasMedia;
    use HasModelContract;
    use HasRecursiveRelationships;
    use HasSlug;

    protected $guarded = [];

    public static function configKey(): string
    {
        return 'category';
    }

    public function getTable(): string
    {
        return shopper_table('categories');
    }

    /**
     * @return array<array-key, array<string>>
     */
    public function getCustomPaths(): array
    {
        return [
            [
                'name' => 'slug_path',
                'column' => 'slug',
                'separator' => '/',
            ],
        ];
    }

    public function updateStatus(bool $status = true): void
    {
        $this->update(['is_enabled' => $status]);
    }

    public function getLabelOptionName(): string
    {
        return $this->parent
            ? $this->parent->getLabelOptionName().' / '.$this->name
            : $this->name;
    }

    /**
     * @param  Builder<Category>  $query
     * @return Builder<Category>
     */
    public function scopeEnabled(Builder $query): Builder
    {
        return $query->where('is_enabled', true);
    }

    /**
     * @return HasManyOfDescendants<static, $this>
     */
    public function descendantCategories(): HasManyOfDescendants
    {
        return $this->hasManyOfDescendants(static::class, 'parent_id');
    }

    /**
     * @return MorphToMany<Product, $this>
     */
    public function products(): MorphToMany
    {
        return $this->morphToMany(config('shopper.models.product'), 'productable', shopper_table('product_has_relations'));
    }

    protected static function newFactory(): CategoryFactory
    {
        return CategoryFactory::new();
    }

    protected function casts(): array
    {
        return [
            'is_enabled' => 'boolean',
            'metadata' => 'array',
        ];
    }
}
