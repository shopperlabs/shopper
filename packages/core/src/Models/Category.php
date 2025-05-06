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
use Shopper\Core\Observers\CategoryObserver;
use Shopper\Core\Traits\HasSlug;
use Spatie\MediaLibrary\HasMedia as SpatieHasMedia;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;
use Staudenmeir\LaravelAdjacencyList\Eloquent\Relations\HasManyOfDescendants;

/**
 * @property-read int $id
 * @property string $name
 * @property string $slug
 * @property bool $is_enabled
 * @property int|null $parent_id
 * @property null|self $parent
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

    protected function casts(): array
    {
        return [
            'is_enabled' => 'boolean',
            'metadata' => 'array',
        ];
    }

    protected static function newFactory(): CategoryFactory
    {
        return CategoryFactory::new();
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
        $this->is_enabled = $status;

        $this->save();
    }

    /**
     * Use to display custom label into filament relationship select form component
     */
    public function getLabelOptionName(): string
    {
        return $this->parent
            ? $this->parent->getLabelOptionName() . ' / ' . $this->name
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
}
