<?php

namespace Shopper\Framework\Models\Shop;

use Illuminate\Database\Eloquent\Model;
use Shopper\Framework\Models\Channel;
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
        'facebook_url',
        'twitter_url',
        'instagram_url',
        'linkedin_url',
        'latitude',
        'latitude',
    ];

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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function members()
    {
        return $this->belongsToMany(ShopMember::class, shopper_table('shop_members'), 'shop_id', 'user_id');
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
}
