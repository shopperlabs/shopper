<?php

declare(strict_types=1);

namespace Shopper\Traits;

use Illuminate\Auth\Access\AuthorizationException;
use Shopper\Models\Contracts\ShopperUser;
use Shopper\Models\Permission;
use Shopper\Models\Role;

trait AuthorizesTeamManagement
{
    protected function authorizeTeamAccess(?Role $role = null): void
    {
        $this->authorize('system.users');

        $this->denyAdministratorRole($role);
    }

    protected function authorizeTeamManagement(?Role $role = null): void
    {
        $this->authorize('system.settings');

        $this->denyAdministratorRole($role);
    }

    protected function authorizePermissionDefinition(): void
    {
        $this->authorizeTeamManagement();

        if (! $this->actingUserIsAdmin()) {
            throw new AuthorizationException(__('shopper::notifications.unauthorized.administrator_only'));
        }
    }

    protected function authorizePermissionGrant(Role $role, Permission $permission): void
    {
        $this->authorizeTeamManagement($role);

        if ($this->actingUserIsAdmin()) {
            return;
        }

        if (! $this->actingUser()?->can($permission->name)) {
            throw new AuthorizationException(__('shopper::notifications.unauthorized.permission_scope'));
        }
    }

    protected function actingUserIsAdmin(): bool
    {
        return $this->actingUser()?->isAdmin() ?? false;
    }

    protected function actingUser(): ?ShopperUser
    {
        /** @var ?ShopperUser $user */
        $user = shopper()->auth()->user();

        return $user;
    }

    private function denyAdministratorRole(?Role $role): void
    {
        if ($role?->isAdmin() && ! $this->actingUserIsAdmin()) {
            throw new AuthorizationException(__('shopper::notifications.unauthorized.administrator_role'));
        }
    }
}
