<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Shopper\Core\Database\Factories\CollectionFactory;
use Shopper\Core\Enum\CollectionType;
use Shopper\Core\Models\Contracts\Collection as CollectionContract;
use Shopper\Core\Models\Traits\HasMedia;
use Shopper\Core\Models\Traits\HasSlug;
use Shopper\Core\Queries\CollectionProductsQuery;
use Shopper\Core\Traits\HasModelContract;
use Spatie\MediaLibrary\HasMedia as SpatieHasMedia;

/**
 * @property-read int $id
 * @property-read string $name
 * @property-read string $slug
 * @property-read CollectionType $type
 * @property-read ?string $description
 * @property-read ?string $match_conditions
 * @property-read ?string $sort
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 * @property-read CarbonInterface $published_at
 * @property-read array<string, mixed>|null $metadata
 * @property-read ?string $seo_title
 * @property-read ?string $seo_description
 * @property-read EloquentCollection<int, CollectionRule> $rules
 * @property-read EloquentCollection<int, Zone> $zones
 * @property-read EloquentCollection<int, Product> $products
 */
class Collection extends Model implements CollectionContract, SpatieHasMedia
{
    /** @use HasFactory<CollectionFactory> */
    use HasFactory;

    use HasMedia;
    use HasModelContract;
    use HasSlug;

    protected $guarded = [];

    public static function configuredClass(): string
    {
        return config('shopper.models.collection', static::class);
    }

    public function getTable(): string
    {
        return shopper_table('collections');
    }

    public function isAutomatic(): bool
    {
        return $this->type === CollectionType::Auto;
    }

    public function isManual(): bool
    {
        return ! $this->isAutomatic();
    }

    /**
     * Get all products for this collection.
     * For manual collections, returns the attached products.
     * For automatic collections, evaluates rules and returns matching products.
     *
     * @return EloquentCollection<int, Contracts\Product>
     */
    public function getProducts(): EloquentCollection
    {
        return (new CollectionProductsQuery)->get($this);
    }

    /**
     * Get the query builder for this collection's products.
     * Useful for pagination or further filtering.
     *
     * @return Builder<Contracts\Product>
     */
    public function productsQuery(): Builder
    {
        return (new CollectionProductsQuery)->query($this);
    }

    public function firstRule(): ?string
    {
        /** @var CollectionRule $collectionRule */
        $collectionRule = $this->rules->first();

        if ($this->isAutomatic()) {
            $words = $collectionRule->getFormattedRule().' '.$collectionRule->getFormattedOperator().' '.$collectionRule->getFormattedValue();
            $rules = $this->rules()->count();

            return $words.' '.($rules >= 2 ? '+ '.($rules - 1).__('shopper::words.other') : ''); // @phpstan-ignore-line
        }

        return null;
    }

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeManual(Builder $query): Builder
    {
        return $query->where('type', CollectionType::Manual);
    }

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeAutomatic(Builder $query): Builder
    {
        return $query->where('type', CollectionType::Auto);
    }

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * @return MorphToMany<Product, $this>
     */
    public function products(): MorphToMany
    {
        return $this->morphToMany(config('shopper.models.product'), 'productable', shopper_table('product_has_relations'));
    }

    /**
     * @return MorphToMany<Zone, $this>
     */
    public function zones(): MorphToMany
    {
        return $this->morphToMany(Zone::class, 'zonable', shopper_table('zone_has_relations'));
    }

    /**
     * @return HasMany<CollectionRule, $this>
     */
    public function rules(): HasMany
    {
        return $this->hasMany(CollectionRule::class, 'collection_id');
    }

    protected static function newFactory(): CollectionFactory
    {
        return CollectionFactory::new();
    }

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'metadata' => 'array',
            'type' => CollectionType::class,
        ];
    }
}
