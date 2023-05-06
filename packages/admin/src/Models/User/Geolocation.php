<?php

declare(strict_types=1);

namespace Shopper\Framework\Models\User;

use Illuminate\Database\Eloquent\Model;

class Geolocation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function getTable(): string
    {
        return shopper_table('users_geolocation_history');
    }
}
