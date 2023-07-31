<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Illuminate\Database\Eloquent\Model;

class Geolocation extends Model
{
    protected $guarded = [];

    public function getTable(): string
    {
        return shopper_table('users_geolocation_history');
    }
}
