<?php

declare(strict_types=1);

namespace Database\Seeders;

use Database\Seeders\Auth\PermissionRoleTableSeeder;
use Database\Seeders\Auth\PermissionsTableSeeder;
use Database\Seeders\Auth\RolesTableSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Traits\Database\TruncateTable;

final class AuthTableSeeder extends Seeder
{
    use TruncateTable;

    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        app()['cache']->forget('spatie.permission.cache');

        $this->truncateMultiple([
            config('permission.table_names.model_has_permissions'),
            config('permission.table_names.model_has_roles'),
            config('permission.table_names.role_has_permissions'),
            config('permission.table_names.permissions'),
            config('permission.table_names.roles'),
            'users',
        ]);

        $this->call(RolesTableSeeder::class);
        $this->call(PermissionsTableSeeder::class);
        $this->call(PermissionRoleTableSeeder::class);

        Schema::enableForeignKeyConstraints();
    }
}
