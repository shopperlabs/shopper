<?php

use Illuminate\Database\Seeder;
use Shopper\Framework\Traits\Database\DisableForeignKeys;
use Spatie\Permission\Models\Role;

class PermissionRoleTableSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seed.
     */
    public function run()
    {
        $this->disableForeignKeys();

        // Create Roles
        Role::create([
            'name' => config('shopper.config.users.admin_role'),
            'display_name' => 'Administrator',
            'description' => 'Site administrator with access to shop and developer tools.'
        ]);

        Role::create([
            'name' => 'manager',
            'display_name' => 'Manager',
            'description' => 'Site manager with access to shop and publishing menus.'
        ]);

        Role::create([
            'name' => config('shopper.config.users.default_role'),
            'display_name' => 'User',
            'description' => 'Site customer role with access on front site.'
        ]);

        $this->enableForeignKeys();
    }
}
