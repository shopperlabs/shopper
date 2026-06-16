<?php

declare(strict_types=1);

namespace Shopper\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Shopper\Models\Permission;

final class PermissionsTableSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        Permission::query()->create([
            'name' => 'system.dashboard',
            'group_name' => 'system',
            'display_name' => __('shopper::permissions.system.dashboard.display_name'),
            'description' => __('shopper::permissions.system.dashboard.description'),
            'can_be_removed' => false,
        ]);

        Permission::query()->create([
            'name' => 'system.settings',
            'group_name' => 'system',
            'display_name' => __('shopper::permissions.system.settings.display_name'),
            'description' => __('shopper::permissions.system.settings.description'),
            'can_be_removed' => false,
        ]);

        Permission::query()->create([
            'name' => 'system.users',
            'group_name' => 'system',
            'display_name' => __('shopper::permissions.system.users.display_name'),
            'description' => __('shopper::permissions.system.users.description'),
            'can_be_removed' => false,
        ]);

        Permission::generate('brands', 'catalog');
        Permission::generate('categories', 'catalog');
        Permission::generate('collections', 'catalog');

        Permission::generate('products');
        Permission::generate('products.variants', 'products');
        Permission::generate('attributes', 'products');
        Permission::generate('suppliers', 'products');
        Permission::generate('tags', 'products');
        Permission::generate('reviews', 'products');

        Permission::generate('orders', 'sales');
        Permission::generate('discounts', 'sales');
        Permission::generate('campaigns', 'sales');

        Permission::generate('customers');

        Permission::generate('inventories', 'inventory');

        Schema::enableForeignKeyConstraints();
    }
}
