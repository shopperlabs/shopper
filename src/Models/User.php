<?php

namespace Shopper\Framework\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Shopper\Framework\Models\Shop\Shop;
use Shopper\Framework\Models\Shop\ShopMember;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property string last_name
 * @property string first_name
 * @property string avatar_type
 * @property string email
 * @property string avatar_location
 * @property boolean is_superuser
 */
class User extends Authenticatable
{
    use Notifiable,
        HasRoles,
        SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'avatar_type',
        'avatar_location',
        'password',
        'api_token',
        'timezone',
        'last_login_at',
        'last_login_ip',
        'is_superuser'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_superuser'      => 'boolean'
    ];

    /**
     * @var array
     */
    protected $dates = [
        'last_login_at',
        'password_changed_at',
    ];

    /**
     * The dynamic attributes from mutators that should be returned with the user object.
     *
     * @var array
     */
    protected $appends = [
        'full_name',
        'picture',
    ];

    /**
     * Define if user is an admin
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->hasRole(config('shopper.users.admin_role'));
    }

    /**
     * Define if user is an super admin
     *
     * @return bool
     */
    public function isSuperAdmin()
    {
        return $this->isAdmin() && $this->is_superuser;
    }

    /**
     * Return User Fullname
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        return $this->last_name
            ? $this->first_name . ' ' . $this->last_name
            : $this->first_name;
    }

    /**
     * Get user profile picture
     *
     * @return \Illuminate\Contracts\Routing\UrlGenerator|mixed|string
     */
    public function getPictureAttribute()
    {
        switch ($this->avatar_type) {
            case 'gravatar':
                return gravatar()->get($this->email);

            case 'storage':
                return url('storage/' . $this->avatar_location);
        }
    }

    /**
     * Get User Shop
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function shop()
    {
        return $this->hasOne(Shop::class, 'owner_id');
    }

    /**
     * Shop member
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function shopMember()
    {
        return $this->belongsToMany(User::class, 'shop_members', 'user_id', 'shop_id');
    }
}
