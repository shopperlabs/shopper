<?php

namespace Shopper\Framework\Models\Shop\Product;

use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Shopper\Framework\Contracts\ReviewRateable;
use Shopper\Framework\Models\Shop\Channel;
use Shopper\Framework\Models\Traits\CanHaveDiscount;
use Shopper\Framework\Models\Traits\Filetable;
use Shopper\Framework\Models\Traits\HasPrice;
use Shopper\Framework\Models\Traits\HasSlug;
use Shopper\Framework\Models\Traits\HasStock;
use Shopper\Framework\Models\Traits\ReviewRateable as ReviewRateableTrait;

class Product extends Model implements ReviewRateable
{
    use Filetable,
        HasStock,
        HasPrice,
        HasSlug,
        CanHaveDiscount,
        SoftDeletes,
        SoftCascadeTrait,
        ReviewRateableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'sku',
        'barcode',
        'description',
        'security_stock',
        'featured',
        'is_visible',
        'brand_id',
        'parent_id',
        'old_price_amount',
        'price_amount',
        'cost_amount',
        'type',
        'published_at',
        'backorder',
        'requires_shipping',
        'weight_value',
        'weight_unit',
        'height_value',
        'height_unit',
        'width_value',
        'width_unit',
        'depth_value',
        'depth_unit',
        'volume_value',
        'seo_title',
        'seo_description',
    ];

    /**
     * Cascade soft delete tables.
     *
     * @var string[]
     */
    protected $softCascade = ['variations'];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'featured' => 'boolean',
        'is_visible' => 'boolean',
        'requires_shipping' => 'boolean',
        'published_at' => 'datetime',
    ];

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable(): string
    {
        return shopper_table('products');
    }

    /**
     * Set the proper slug attribute.
     *
     * @param  string  $slug
     */
    public function setSlugAttribute(string $slug)
    {
        $this->attributes['slug'] = $this->generateUniqueSlug($slug, true);
    }

    /**
     * Get the formatted price value.
     *
     * @return string|null
     */
    public function getFormattedPriceAttribute(): ?string
    {
        if ($this->parent_id) {
            return $this->price_amount
                ? $this->formattedPrice($this->price_amount)
                : ($this->parent->price_amount ? $this->formattedPrice($this->parent->price_amount) : null);
        } else {
            return $this->price_amount
                ? $this->formattedPrice($this->price_amount)
                : null;
        }
    }

    /**
     * Get the stock of all variations.
     *
     * @return int
     */
    public function getVariationsStockAttribute(): int
    {
        $stock = 0;

        if ($this->variations->isNotEmpty()) {
            foreach ($this->variations as $variation) {
                $stock += $variation->stock;
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
        return $this->hasMany(self::class, 'parent_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class);
    }

    public function channels(): MorphToMany
    {
        return $this->morphedByMany(Channel::class, 'productable', 'product_has_relations');
    }

    public function relatedProducts(): MorphToMany
    {
        return $this->morphedByMany(config('shopper.system.models.product'), 'productable', 'product_has_relations');
    }

    public function categories(): MorphToMany
    {
        return $this->morphedByMany(config('shopper.system.models.category'), 'productable', 'product_has_relations');
    }

    public function collections(): MorphToMany
    {
        return $this->morphedByMany(config('shopper.system.models.collection'), 'productable', 'product_has_relations');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(config('shopper.system.models.brand'), 'brand_id');
    }

    public function attributes(): HasMany
    {
        return $this->hasMany(ProductAttribute::class);
    }
}
