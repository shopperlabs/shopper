<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Shopper\Core\Traits\HasMedia;
use Shopper\Core\Traits\HasSlug;
use Spatie\MediaLibrary\HasMedia as SpatieHasMedia;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;
use Staudenmeir\LaravelAdjacencyList\Eloquent\Relations\HasManyOfDescendants;

/**
 * @property-read int $id
 * @property-read string $name
 * @property-read string $slug
 * @property-read int|null $parent_id
 * @property-read null|self $parent
 */
class Category extends Model implements SpatieHasMedia
{
    use HasFactory;
    use HasMedia;
    use HasRecursiveRelationships;
    use HasSlug;

    protected $guarded = [];

    protected $casts = [
        'is_enabled' => 'boolean',
    ];

    public function getTable(): string
    {
        return shopper_table('categories');
    }

    public function scopeEnabled(Builder $query): Builder
    {
        return $query->where('is_enabled', true);
    }

    public function getParentNameAttribute(): ?string
    {
        return $this->parent?->name;
    }

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

    public function descendantCategories(): HasManyOfDescendants
    {
        return $this->hasManyOfDescendants(self::class, 'parent_id');
    }

    public function products(): MorphToMany
    {
        return $this->morphToMany(config('shopper.models.product'), 'productable', 'product_has_relations');
    }
}
