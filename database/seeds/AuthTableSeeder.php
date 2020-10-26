<?php

use Illuminate\Database\Seeder;
use Shopper\Framework\Traits\Database\DisableForeignKeys;
use Shopper\Framework\Traits\Database\Seedable;
use Shopper\Framework\Traits\Database\TruncateTable;

class AuthTableSeeder extends Seeder
{
    use DisableForeignKeys,
        TruncateTable,
        Seedable;

    protected $seedersPath = __DIR__.'/Auth/';

    /**
     * Run the database seeds.
     */
    public function run()
    {
        $this->disableForeignKeys();

        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        $this->truncateMultiple([
            config('permission.table_names.model_has_permissions'),
            config('permission.table_names.model_has_roles'),
            config('permission.table_names.role_has_permissions'),
            config('permission.table_names.permissions'),
            config('permission.table_names.roles'),
            'users',
        ]);

        $this->seed('RolesTableSeeder');
        $this->seed('PermissionsTableSeeder');
        $this->seed('PermissionRoleTableSeeder');

        $this->enableForeignKeys();
    }
}
