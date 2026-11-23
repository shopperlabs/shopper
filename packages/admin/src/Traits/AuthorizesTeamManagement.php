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

        $this->denyProtectedRole($role);
    }

    protected function authorizeTeamManagement(?Role $role = null): void
    {
        $this->authorize('system.settings');

        $this->denyProtectedRole($role);
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

    protected function authorizeRoleAssignment(Role $role): void
    {
        $this->authorizeTeamAccess($role);

        if (! $this->canAssignRole($role)) {
            throw new AuthorizationException(__('shopper::notifications.unauthorized.permission_scope'));
        }
    }

    protected function canAssignRole(Role $role): bool
    {
        if ($this->actingUserIsAdmin()) {
            return true;
        }

        return ! $role->isAdmin()
            && $role->permissions->pluck('name')->every(fn (string $permission): bool => $this->actingUser()?->can($permission) ?? false);
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

    private function denyProtectedRole(?Role $role): void
    {
        if ($role?->name === config('shopper.admin.roles.user')) {
            throw new AuthorizationException(__('shopper::notifications.unauthorized.customer_role'));
        }

        if ($role?->isAdmin() && ! $this->actingUserIsAdmin()) {
            throw new AuthorizationException(__('shopper::notifications.unauthorized.administrator_role'));
        }
    }
}
