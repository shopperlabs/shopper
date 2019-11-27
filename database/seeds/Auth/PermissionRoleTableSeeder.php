<?php

use Illuminate\Database\Seeder;
use Shopper\Framework\Traits\Database\DisableForeignKeys;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

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
        $admin = Role::create([
            'name' => config('shopper.users.admin_role'),
            'display_name' => 'Administrator',
            'description' => 'Site administrator with access to shop and developer tools.'
        ]);

        $executive = Role::create([
            'name' => 'seller',
            'display_name' => 'Seller',
            'description' => 'Merchant of the site with the access for the publications of its products'
        ]);

        Role::create([
            'name' => config('shopper.users.default_role'),
            'display_name' => 'User',
            'description' => 'Site customer role with acces on front site.'
        ]);

        // Create Permissions
        $permissions = [
            'view-backend'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // ALWAYS GIVE ADMIN ROLE ALL PERMISSIONS
        $admin->givePermissionTo(Permission::all());

        // Assign Permissions to other Roles
        $executive->givePermissionTo('view-backend');

        $this->enableForeignKeys();
    }
}
