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
            'name' => config('shopper.users.admin_role'),
            'display_name' => 'Administrator',
            'description' => 'Site administrator with access to shop and developer tools.'
        ]);

        Role::create([
            'name' => 'seller',
            'display_name' => 'Seller',
            'description' => 'Merchant of the site with the access for the publications of its products'
        ]);

        Role::create([
            'name' => config('shopper.users.default_role'),
            'display_name' => 'User',
            'description' => 'Site customer role with acces on front site.'
        ]);

        $this->enableForeignKeys();
    }
}
