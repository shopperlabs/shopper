<?php

declare(strict_types=1);

namespace Database\Seeders\Auth;

use Illuminate\Database\Seeder;
use Shopper\Framework\Models\User\Permission;
use Shopper\Framework\Traits\Database\DisableForeignKeys;
use Spatie\Permission\Models\Role;

class PermissionRoleTableSeeder extends Seeder
{
    use DisableForeignKeys;

    public function run(): void
    {
        $this->disableForeignKeys();

        $administrator = Role::query()->where('name', config('shopper.system.users.admin_role'))->firstOrFail();

        $permissions = Permission::all();

        $administrator->permissions()->sync($permissions->pluck('id')->all());

        $this->enableForeignKeys();
    }
}
