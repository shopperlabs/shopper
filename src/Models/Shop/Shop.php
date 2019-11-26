<?php

namespace Shopper\Framework\Models\Shop;

use Illuminate\Database\Eloquent\Model;
use Shopper\Framework\Models\User;

class Shop extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'logo_url',
        'size_id',
        'owner_id',
        'facebook_url',
        'twitter_url',
        'instagram_url',
        'linkedin_url'
    ];

    /**
     * Get Shop size
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function size()
    {
        return $this->belongsTo(ShopSize::class, 'size_id');
    }

    /**
     * Get owner of the shop
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get shop members
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function members()
    {
        return $this->belongsToMany(ShopMember::class, 'shop_members', 'shop_id', 'user_id');
    }
}
