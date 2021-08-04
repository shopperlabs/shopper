<?php

namespace Shopper\Framework\Models\User;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Shopper\Framework\Models\Traits\ShopperUser;

/**
 * Class User
 * @package Shopper\Framework\Models\User
 *
 * @todo Remove all references to this user class
 * @deprecated remove in the next minor release (Keep for now for backwards compatibility)
 */
class User extends Authenticatable
{
    use ShopperUser;
}
