<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Shopper\Core\Database\Factories\CategoryFactory;
use Shopper\Core\Models\Contracts\Category as CategoryContract;
use Shopper\Core\Models\Traits\HasMedia;
use Shopper\Core\Models\Traits\HasSlug;
use Shopper\Core\Observers\CategoryObserver;
use Shopper\Core\Traits\HasModelContract;
use Spatie\MediaLibrary\HasMedia as SpatieHasMedia;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;
use Staudenmeir\LaravelAdjacencyList\Eloquent\Relations\HasManyOfDescendants;

/**
 * @property-read int $id
 * @property-read string $name
 * @property-read string $slug
 * @property-read bool $is_enabled
 * @property-read ?int $parent_id
 * @property-read ?static $parent
 * @property-read Collection<int, Product> $products
 */
#[ObservedBy(CategoryObserver::class)]
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
        // @phpstan-ignore-next-line
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
