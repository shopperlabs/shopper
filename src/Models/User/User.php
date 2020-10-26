<?php

namespace Shopper\Framework\Models\User;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Shopper\Framework\Models\Traits\CanHaveDiscount;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable,
        HasRoles,
        CanHaveDiscount,
        SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'last_login_at',
        'last_login_ip',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
    ];

    /**
     * @var array
     */
    protected $dates = [
        'email_verified_at',
        'last_login_at',
    ];

    /**
     * The dynamic attributes from mutators that should be returned with the user object.
     *
     * @var array
     */
    protected $appends = [
        'full_name',
        'picture',
        'roles_label',
    ];

    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable(): string
    {
        return shopper_table('users');
    }

    /**
     * Define if user is an admin
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->hasRole(config('shopper.system.users.admin_role'));
    }

    /**
     * Define if an user account is verified.
     *
     * @return bool
     */
    public function isVerified()
    {
        return $this->email_verified_at !== null;
    }

    /**
     * Return User Full Name.
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
     * Get User roles name.
     *
     * @return string
     */
    public function getRolesLabelAttribute()
    {
        $roles = $this->roles()->pluck('display_name')->toArray();

        if (\count($roles)) {
            return implode(', ', array_map(function ($item) {
                return ucwords($item);
            }, $roles));
        }

        return 'N/A';
    }

    /**
     * Get user profile picture.
     *
     * @return \Illuminate\Contracts\Routing\UrlGenerator|mixed|string
     */
    public function getPictureAttribute()
    {
        switch ($this->avatar_type) {
            case 'gravatar':
                return gravatar()->get($this->email);

            case 'storage':
                return Storage::disk(config('shopper.system.storage.disks.avatars'))->url($this->avatar_location);
        }
    }

    /**
     * Get all User Addresses.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }
}
