<?php

declare(strict_types=1);

namespace Shopper\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Shopper\Models\Permission;
use Shopper\Models\Role;

final class PermissionRoleTableSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        $administrator = Role::query()
            ->where('name', config('shopper.admin.roles.admin'))
            ->firstOrFail();

        $permissions = Permission::all();

        $administrator->permissions()
            ->sync($permissions->pluck('id')->all());

        Schema::enableForeignKeyConstraints();
    }
}
