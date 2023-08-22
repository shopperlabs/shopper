<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Spatie\Permission\Models\Role as SpatieRole;

/**
 * @property-read int $id
 * @property string $name
 * @property string $guard_name
 * @property string $display_name
 * @property string|null $description
 */
final class Role extends SpatieRole
{
    public function isAdmin(): bool
    {
        return $this->name === config('shopper.core.users.admin_role');
    }
}
