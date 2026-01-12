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
 * @property-read CarbonInterface $published_at
 * @property-read array<string, mixed>|null $metadata
 * @property-read ?string $seo_title
 * @property-read ?string $seo_description
 * @property-read EloquentCollection<int, CollectionRule> $rules
 * @property-read EloquentCollection<int, Contracts\Product> $products
 */
class Collection extends Model implements CollectionContract, SpatieHasMedia
{
    /** @use HasFactory<CollectionFactory> */
    use HasFactory;

    use HasMedia;
    use HasModelContract;
    use HasSlug;

    protected $guarded = [];

    public static function configKey(): string
    {
        return 'collection';
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
     * @param  Builder<Collection>  $query
     * @return Builder<Collection>
     */
    public function scopeManual(Builder $query): Builder
    {
        return $query->where('type', CollectionType::Manual);
    }

    /**
     * @param  Builder<Collection>  $query
     * @return Builder<Collection>
     */
    public function scopeAutomatic(Builder $query): Builder
    {
        return $query->where('type', CollectionType::Auto);
    }

    /**
     * @return MorphToMany<Product, $this>
     */
    public function products(): MorphToMany
    {
        // @phpstan-ignore-next-line
        return $this->morphToMany(config('shopper.models.product'), 'productable', shopper_table('product_has_relations'));
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
