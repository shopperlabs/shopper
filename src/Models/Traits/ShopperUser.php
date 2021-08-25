<?php

namespace Shopper\Framework\Models\Traits;

use Laravel\Cashier\Billable;
use Spatie\Permission\Traits\HasRoles;

trait ShopperUser
{
    use HasRoles;
    use Billable;

    /**
     * Get the table associated with the model.
     */
    public function getTable(): string
    {
        return shopper_table('users');
    }

    /**
     * Define if user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->hasRole(config('shopper.system.users.admin_role'));
    }
}
