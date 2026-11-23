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

        $administrator->syncPermissions(Permission::all());

        $manager = Role::query()
            ->where('name', config('shopper.admin.roles.manager'))
            ->firstOrFail();

        $manager->syncPermissions(
            Permission::query()
                ->whereIn('group_name', ['catalog', 'products', 'sales'])
                ->orWhereIn('name', ['system.dashboard', 'customers.browse', 'customers.read', 'customers.create', 'customers.edit'])
                ->get()
        );

        Schema::enableForeignKeyConstraints();
    }
}
