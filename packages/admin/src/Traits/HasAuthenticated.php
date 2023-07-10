<?php

declare(strict_types=1);

namespace Shopper\Traits;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Shopper\Facades\Shopper;

trait HasAuthenticated
{
    public function getUser(): Model|Authenticatable|null
    {
        return Shopper::auth()->user();
    }
}
