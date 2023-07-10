<?php

declare(strict_types=1);

namespace Shopper\Core\Models;

use Spatie\Permission\Models\Role as SpatieRole;

/**
 * @property-read int $id
 * @property-read string $name
 * @property-read string $guard_name
 * @property-read string $display_name
 * @property-read string|null $description
 */
final class Role extends SpatieRole
{
    public function isAdmin(): bool
    {
        return $this->name === config('shopper.core.users.admin_role');
    }
}
