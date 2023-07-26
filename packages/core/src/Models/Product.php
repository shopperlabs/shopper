<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Shopper\Core\Contracts\ReviewRateable;
use Shopper\Core\Helpers\Price;
use Shopper\Core\Traits\CanHaveDiscount;
use Shopper\Core\Traits\HasMedia;
use Shopper\Core\Traits\HasPrice;
use Shopper\Core\Traits\HasSlug;
use Shopper\Core\Traits\HasStock;
use Shopper\Core\Traits\ReviewRateable as ReviewRateableTrait;
use Spatie\MediaLibrary\HasMedia as SpatieHasMedia;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

/**
 * @property-read int $id
 * @property-read int|null $parent_id
 * @property-read int|null $price_amount
 * @property-read int|null $old_price_amount
 * @property-read int|null $cost_amount
 */
class Product extends Model implements SpatieHasMedia, ReviewRateable
{
    use CanHaveDiscount;
    use HasFactory;
    use HasMedia;
    use HasPrice;
    use HasRecursiveRelationships;
    use HasSlug;
    use HasStock;
    use ReviewRateableTrait;

    protected $guarded = [];

    protected $casts = [
        'featured' => 'boolean',
        'is_visible' => 'boolean',
        'requires_shipping' => 'boolean',
        'backorder' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function getTable(): string
    {
        return shopper_table('products');
    }

    protected function priceAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100,
        );
    }

    protected function oldPriceAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100,
        );
    }

    protected function costAmount(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100,
        );
    }

    public function getPriceAmount(): ?Price
    {
        if (! $this->price_amount) {
            return null;
        }

        if ($this->parent_id) {
            return $this->price_amount
                ? Price::from($this->price_amount)
                : ($this->parent->price_amount ? Price::from($this->parent->price_amount) : null);
        }

        return Price::from($this->price_amount);
    }

    public function getOldPriceAmount(): ?Price
    {
        if (! $this->old_price_amount) {
            return null;
        }

        return Price::from($this->old_price_amount);
    }

    public function getCostAmount(): ?Price
    {
        if (! $this->cost_amount) {
            return null;
        }

        return Price::from($this->cost_amount);
    }

    public function getVariationsStockAttribute(): int
    {
        $stock = 0;

        if ($this->variations->isNotEmpty()) {
            foreach ($this->variations as $variation) {
                $stock += $variation->stock; // @phpstan-ignore-line
            }
        }

        return $stock;
    }

    public function scopePublish(Builder $query): Builder
    {
        return $query->whereDate('published_at', '<=', now())
            ->where('is_visible', true);
    }

    public function variations(): HasMany
    {
        return $this->hasMany(config('shopper.models.product'), 'parent_id');
    }

    public function channels(): MorphToMany
    {
        return $this->morphedByMany(config('shopper.models.channel'), 'productable', 'product_has_relations');
    }

    public function relatedProducts(): MorphToMany
    {
        return $this->morphedByMany(config('shopper.models.product'), 'productable', 'product_has_relations');
    }

    public function categories(): MorphToMany
    {
        return $this->morphedByMany(config('shopper.models.category'), 'productable', 'product_has_relations');
    }

    public function collections(): MorphToMany
    {
        return $this->morphedByMany(config('shopper.models.collection'), 'productable', 'product_has_relations');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(config('shopper.models.brand'), 'brand_id');
    }

    public function attributes(): HasMany
    {
        return $this->hasMany(ProductAttribute::class);
    }
}
