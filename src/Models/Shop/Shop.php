<?php

namespace Shopper\Framework\Models\Shop;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Shopper\Framework\Models\Channel;
use Shopper\Framework\Models\Currency;
use Shopper\Framework\Models\Ecommerce\Product;
use Shopper\Framework\Models\Inventory;
use Shopper\Framework\Models\User;
use Shopper\Framework\Traits\Mediatable;

class Shop extends Model
{
    use Mediatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'description',
        'address',
        'logo_url',
        'cover_url',
        'country',
        'city',
        'post_code',
        'size_id',
        'owner_id',
        'currency_id',
        'facebook_url',
        'twitter_url',
        'instagram_url',
        'linkedin_url',
        'latitude',
        'latitude',
    ];

    /**
     * Get Shop default logo.
     *
     * @return \Illuminate\Contracts\Routing\UrlGenerator|mixed|string
     */
    public function getLogoFullUrlAttribute()
    {
        if ($this->logo_url) {
            return Storage::disk(config('shopper.storage.disks.uploads'))->url($this->logo_url);
        }

        return null;
    }

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable(): string
    {
        return shopper_table('shops');
    }

    /**
     * Get Shop size.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function size()
    {
        return $this->belongsTo(ShopSize::class, 'size_id');
    }

    /**
     * Get owner of the shop.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get shop members.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function members()
    {
        return $this->hasMany(ShopMember::class, shopper_table('shop_members'), 'shop_id');
    }

    /**
     * Return all shop's inventories.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    /**
     * Return all shop's channels.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function channels()
    {
        return $this->hasMany(Channel::class);
    }

    /**
     * Return all shop's products.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Return the Shop currency.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }
}
