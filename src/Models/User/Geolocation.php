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

    /**
     * Get the table associated with the model.
     */
    public function getTable(): string
    {
        return shopper_table('users_geolocation_history');
    }
}
