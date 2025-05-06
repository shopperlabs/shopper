<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Shopper\Core\Database\Factories\CollectionFactory;
use Shopper\Core\Enum\CollectionType;
use Shopper\Core\Models\Traits\HasMedia;
use Shopper\Core\Traits\HasSlug;
use Spatie\MediaLibrary\HasMedia as SpatieHasMedia;

/**
 * @property-read int $id
 * @property CollectionType $type
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property array<array-key, mixed>|null $metadata
 * @property-read \Illuminate\Support\Collection<int, CollectionRule> $rules
 * @property-read \Illuminate\Support\Collection<int, Product> $products
 */
class Collection extends Model implements SpatieHasMedia
{
    /** @use HasFactory<CollectionFactory> */
    use HasFactory;

    use HasMedia;
    use HasSlug;

    protected $guarded = [];

    public function getTable(): string
    {
        return shopper_table('collections');
    }

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'metadata' => 'array',
            'type' => CollectionType::class,
        ];
    }

    protected static function newFactory(): CollectionFactory
    {
        return CollectionFactory::new();
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
            $words = $collectionRule->getFormattedRule() . ' ' . $collectionRule->getFormattedOperator() . ' ' . $collectionRule->getFormattedValue();
            $rules = $this->rules()->count();

            return $words . ' ' . ($rules >= 2 ? '+ ' . ($rules - 1) . __('shopper::words.other') : ''); // @phpstan-ignore-line
        }

        return null;
    }

    /**
     * @param  Builder<Collection>  $query
     * @return Builder<Collection>
     */
    public function scopeManual(Builder $query): Builder
    {
        return $query->where('type', CollectionType::Manual());
    }

    /**
     * @param  Builder<Collection>  $query
     * @return Builder<Collection>
     */
    public function scopeAutomatic(Builder $query): Builder
    {
        return $query->where('type', CollectionType::Auto());
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
}
