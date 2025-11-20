<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Shopper\Core\Database\Factories\CategoryFactory;
use Shopper\Core\Models\Traits\HasMedia;
use Shopper\Core\Models\Traits\HasSlug;
use Shopper\Core\Observers\CategoryObserver;
use Spatie\MediaLibrary\HasMedia as SpatieHasMedia;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;
use Staudenmeir\LaravelAdjacencyList\Eloquent\Relations\HasManyOfDescendants;

/**
 * @property-read int $id
 * @property-read string $name
 * @property-read string $slug
 * @property-read bool $is_enabled
 * @property-read int|null $parent_id
 * @property-read null|self $parent
 */
#[ObservedBy(CategoryObserver::class)]
class Category extends Model implements SpatieHasMedia
{
    /** @use HasFactory<CategoryFactory> */
    use HasFactory;

    use HasMedia;
    use HasRecursiveRelationships;
    use HasSlug;

    protected $guarded = [];

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

    /**
     * Use to display custom label into filament relationship select form component
     */
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
     * @return HasManyOfDescendants<Category, $this>
     */
    public function descendantCategories(): HasManyOfDescendants
    {
        return $this->hasManyOfDescendants(self::class, 'parent_id');
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
