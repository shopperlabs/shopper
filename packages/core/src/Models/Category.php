<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Shopper\Core\Traits\HasMedia;
use Shopper\Core\Traits\HasSlug;
use Shopper\Observers\CategoryObserver;
use Spatie\MediaLibrary\HasMedia as SpatieHasMedia;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;
use Staudenmeir\LaravelAdjacencyList\Eloquent\Relations\HasManyOfDescendants;

/**
 * @property-read int $id
 * @property string $name
 * @property string $slug
 * @property int|null $parent_id
 * @property null|self $parent
 */
#[ObservedBy(CategoryObserver::class)]
class Category extends Model implements SpatieHasMedia
{
    use HasFactory;
    use HasMedia;
    use HasRecursiveRelationships;
    use HasSlug;

    protected $guarded = [];

    protected $casts = [
        'is_enabled' => 'boolean',
        'metadata' => 'array',
    ];

    public function getTable(): string
    {
        return shopper_table('categories');
    }

    protected function parentName(): ?Attribute
    {
        return Attribute::make(
            get: fn () => $this->parent?->name,
        );
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

    public function updateStatus(bool $status = true): void
    {
        $this->is_enabled = $status;

        $this->save();
    }

    public function scopeEnabled(Builder $query): Builder
    {
        return $query->where('is_enabled', true);
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
