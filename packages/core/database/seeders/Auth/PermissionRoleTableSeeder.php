<?php

declare(strict_types=1);

namespace Shopper\Core\Database\Seeders\Auth;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Models\Permission;
use Shopper\Core\Models\Role;

final class PermissionRoleTableSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        $administrator = Role::query()
            ->where('name', config('shopper.core.users.admin_role'))
            ->firstOrFail();

        $permissions = Permission::all();

        $administrator->permissions()
            ->sync($permissions->pluck('id')->all());

        Schema::enableForeignKeyConstraints();
    }
}
