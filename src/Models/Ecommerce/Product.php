<?php

namespace Shopper\Framework\Models\Ecommerce;

use Illuminate\Database\Eloquent\Model;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money;
use Shopper\Framework\Contracts\ReviewRateable;
use Shopper\Framework\Models\Shop\Shop;
use Shopper\Framework\Models\Traits\CanHaveDiscount;
use Shopper\Framework\Models\Traits\HasStock;
use Shopper\Framework\Models\Traits\ReviewRateable as ReviewRateableTrait;
use Shopper\Framework\Traits\Mediatable;

class Product extends Model implements ReviewRateable
{
    use Mediatable,
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
        'brand_id',
        'parent_id',
        'shop_id',
        'price',
        'min_price',
        'max_price',
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
        'volume_unit',
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
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['previewImage'];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($product) {
            $product->update(['slug' => $product->name]);
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
     * @param  string $value
     */
    public function setSlugAttribute($value)
    {
        if (static::where('slug', $slug = str_slug($value))->exists()) {
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
        if ($this->price) {
            $money = new Money($this->price, new Currency(config('shopper.currency')));
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
        return $this->belongsToMany(config('shopper.models.category'), shopper_table('category_product'), 'product_id');
    }

    /**
     * Return relation related to collections of the current product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function collections()
    {
        return $this->belongsToMany(config('shopper.models.collection'), shopper_table('collection_product'), 'product_id');
    }

    /**
     * Return brand related to the current product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function brand()
    {
        return $this->belongsTo(config('shopper.models.brand'), 'brand_id');
    }

    /**
     * Return shop related to the current product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }
}
