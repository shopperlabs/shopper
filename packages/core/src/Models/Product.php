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
use Shopper\Core\Database\Factories\ProductFactory;
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
 * @property int|null $parent_id
 * @property string $name
 * @property string|null $slug
 * @property string|null $sku
 * @property string|null $barcode
 * @property bool $featured
 * @property int|null $price_amount
 * @property int|null $old_price_amount
 * @property int|null $cost_amount
 * @property int|null $security_stock
 * @property string|null $seo_title
 * @property string|null $seo_description
 * @property \Carbon\Carbon|null $published_at
 * @property-read int $stock
 */
class Product extends Model implements ReviewRateable, SpatieHasMedia
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
        'require_shipping' => 'boolean',
        'backorder' => 'boolean',
        'published_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function getTable(): string
    {
        return shopper_table('products');
    }

    protected static function newFactory(): ProductFactory
    {
        return ProductFactory::new();
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

    public function variantsStock(): Attribute
    {
        $stock = 0;

        if ($this->variants->isNotEmpty()) {
            foreach ($this->variants as $variant) {
                $stock += $variant->stock; // @phpstan-ignore-line
            }
        }

        return Attribute::make(
            get: fn () => $stock,
        );
    }

    public function scopePublish(Builder $query): Builder
    {
        return $query->whereDate('published_at', '<=', now())
            ->where('is_visible', true);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(config('shopper.models.product'), 'parent_id');
    }

    public function channels(): MorphToMany
    {
        return $this->morphedByMany(config('shopper.models.channel'), 'productable', shopper_table('product_has_relations'));
    }

    public function relatedProducts(): MorphToMany
    {
        return $this->morphedByMany(config('shopper.models.product'), 'productable', shopper_table('product_has_relations'));
    }

    public function categories(): MorphToMany
    {
        return $this->morphedByMany(config('shopper.models.category'), 'productable', shopper_table('product_has_relations'));
    }

    public function collections(): MorphToMany
    {
        return $this->morphedByMany(config('shopper.models.collection'), 'productable', shopper_table('product_has_relations'));
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(config('shopper.models.brand'), 'brand_id');
    }

    public function attributes(): HasMany
    {
        return $this->hasMany(AttributeProduct::class);
    }
}
