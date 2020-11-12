<?php

namespace Shopper\Framework\Models\Shop\Product;

use Illuminate\Database\Eloquent\Model;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money;
use Shopper\Framework\Contracts\ReviewRateable;
use Shopper\Framework\Models\Traits\CanHaveDiscount;
use Shopper\Framework\Models\Traits\Filetable;
use Shopper\Framework\Models\Traits\HasStock;
use Shopper\Framework\Models\Traits\ReviewRateable as ReviewRateableTrait;

class Product extends Model implements ReviewRateable
{
    use Filetable,
        HasStock,
        CanHaveDiscount,
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
        'published_at'
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
    public function getTable()
    {
        return shopper_table('products');
    }

    /**
     * Set the proper slug attribute.
     *
     * @param  string  $value
     */
    public function setSlugAttribute($value)
    {
        if (static::query()->where('slug', $slug = str_slug($value))->exists()) {
            $slug = "{$slug}-{$this->id}";
        }

        $this->attributes['slug'] = $slug;
    }

    /**
     * Get the formatted price value.
     *
     * @return string|null
     */
    public function getFormattedPriceAttribute()
    {
        if ($this->price_amount) {
            $money = new Money($this->price_amount, new Currency(shopper_currency()));
            $currencies = new ISOCurrencies();

            $numberFormatter = new \NumberFormatter(app()->getLocale(), \NumberFormatter::CURRENCY);
            $moneyFormatter = new IntlMoneyFormatter($numberFormatter, $currencies);

            return $moneyFormatter->format($money);
        }

        return null;
    }

    /**
     * Return relation related to categories of the current product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(config('shopper.system.models.category'), shopper_table('category_product'), 'product_id');
    }

    /**
     * Return relation related to collections of the current product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function collections()
    {
        return $this->belongsToMany(config('shopper.system.models.collection'), shopper_table('collection_product'), 'product_id');
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
