<?php

namespace Shopper\Framework\Models;

use Spatie\Permission\Models\Role as SpatieRole;

/**
 * @property string name
 */
class Role extends SpatieRole
{
    /**
     * Determine if User is admin
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->name === config('shopper.users.admin_role');
    }
}
