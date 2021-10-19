<?php

namespace Database\Seeders\Auth;

use Illuminate\Database\Seeder;
use Shopper\Framework\Models\User\Permission;
use Shopper\Framework\Traits\Database\DisableForeignKeys;

class PermissionsTableSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seed.
     */
    public function run()
    {
        $this->disableForeignKeys();

        Permission::query()->create([
            'name' => 'access_dashboard',
            'group_name' => 'system',
            'display_name' => __('Access Dashboard'),
            'description' => __('This permission allow user to access to the dashboard.'),
            'can_be_removed' => false,
        ]);

        Permission::query()->create([
            'name' => 'access_setting',
            'group_name' => 'system',
            'display_name' => __('Access Setting'),
            'description' => __('This permission allow user to view the setting page.'),
            'can_be_removed' => false,
        ]);

        Permission::query()->create([
            'name' => 'view_users',
            'group_name' => 'system',
            'display_name' => __('Views Users'),
            'description' => __('This permission allow user to access to the administrator area.'),
            'can_be_removed' => false,
        ]);

        Permission::query()->create([
            'name' => 'manage_mail',
            'group_name' => 'system',
            'display_name' => __('Manage mail setting'),
            'description' => __('This permission allow user to manage the mail configuration with template.'),
            'can_be_removed' => false,
        ]);

        Permission::query()->create([
            'name' => 'impersonate',
            'group_name' => 'system',
            'display_name' => __('Impersonate User'),
            'description' => __('This permission allow user to logged with the account of another user.'),
            'can_be_removed' => false,
        ]);

        Permission::query()->create([
            'name' => 'view_analytics',
            'group_name' => 'system',
            'display_name' => __('Views & Reports analytics'),
            'description' => __('This permission allow user to view, analyze and make reports statistics for shop.'),
            'can_be_removed' => false,
        ]);

        Permission::query()->create([
            'name' => 'setting_analytics',
            'group_name' => 'system',
            'display_name' => __('Manage Analytics setting'),
            'description' => __('This permission allow user to add, update, and remove analytics settings such as Google Analytics, Facebook Pixel and more.'),
            'can_be_removed' => false,
        ]);

        /*
         * Shop management default permissions.
         */
        Permission::generate('brands');
        Permission::generate('categories');
        Permission::generate('collections');
        Permission::generate('products');
        Permission::generate('customers');
        Permission::generate('orders');
        Permission::generate('discounts');
        Permission::generate('inventories', 'products');
        Permission::generate('attributes', 'products');

        $this->enableForeignKeys();
    }
}
