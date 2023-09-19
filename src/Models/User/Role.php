<?php

declare(strict_types=1);

namespace Shopper\Framework\Models\User;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    public function isAdmin(): bool
    {
        return $this->name === config('shopper.system.users.admin_role');
    }
}
