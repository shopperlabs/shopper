<?php

namespace Shopper\Framework\Models\Shop\Product;

use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Shopper\Framework\Contracts\ReviewRateable;
use Shopper\Framework\Models\Shop\Channel;
use Shopper\Framework\Models\Traits\CanHaveDiscount;
use Shopper\Framework\Models\Traits\Filetable;
use Shopper\Framework\Models\Traits\HasPrice;
use Shopper\Framework\Models\Traits\HasStock;
use Shopper\Framework\Models\Traits\ReviewRateable as ReviewRateableTrait;

class Product extends Model implements ReviewRateable
{
    use Filetable,
        HasStock,
        HasPrice,
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
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'published_at',
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['files'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'formattedPrice',
        'variationsStock',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            $model->update(['slug' => $model->name]);
        });
    }

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
     * @param  string  $value
     */
    public function setSlugAttribute(string $value)
    {
        $slug = str_slug($value);

        if (! $this->parent_id) {
            if (static::query()->where('slug', $slug)->exists()) {
                $slug = "{$slug}-{$this->id}";
            }
        } else {
            if (static::query()->where('slug', $variantSlug = $this->parent->slug. '-'. $slug)->exists()) {
                $slug = "{$variantSlug}-{$this->id}";
            }
        }


        $this->attributes['slug'] = $slug;
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
     * @return int|null
     */
    public function getVariationsStockAttribute(): ?int
    {
        $stock = null;

        if ($this->variations->isNotEmpty()) {
            foreach ($this->variations as $variation) {
                $stock += $variation->stock;
            }

            return $stock;
        }

        return $stock;
    }

    /**
     * Scope a query to only include enabled collection.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublish(Builder $query)
    {
        return $query->whereDate('published_at', '<=', now())
            ->where('is_visible', true);
    }

    /**
     * Return products variantes.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function variations()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * Return product parent.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(self::class);
    }

    /**
     * Get all of the channels that are assigned this product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function channels()
    {
        return $this->morphedByMany(Channel::class, 'productable', 'product_has_relations');
    }

    /**
     * Get all of the related products that are assigned this product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function relatedProducts()
    {
        return $this->morphedByMany(config('shopper.system.models.product'), 'productable', 'product_has_relations');
    }

    /**
     * Get all of the categories that are assigned this product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function categories()
    {
        return $this->morphedByMany(config('shopper.system.models.category'), 'productable', 'product_has_relations');
    }

    /**
     * Get all of the collections that are assigned this product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function collections()
    {
        return $this->morphedByMany(config('shopper.system.models.collection'), 'productable', 'product_has_relations');
    }

    /**
     * Return brand related to the current product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function brand()
    {
        return $this->belongsTo(config('shopper.system.models.brand'), 'brand_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }
}
