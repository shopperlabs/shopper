<?php

declare(strict_types=1);

namespace Database\Seeders\Auth;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Models\Role;

final class RolesTableSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        Role::create([
            'name' => config('shopper.core.users.admin_role'),
            'display_name' => 'Administrator',
            'description' => 'Site administrator with access to shop admin panel and developer tools.',
            'can_be_removed' => false,
        ]);

        Role::create([
            'name' => 'manager',
            'display_name' => 'Manager',
            'description' => 'Site manager with access to shop admin panel and publishing menus.',
            'can_be_removed' => false,
        ]);

        Role::create([
            'name' => config('shopper.core.users.default_role'),
            'display_name' => 'User',
            'description' => 'Site customer role with access on front site.',
            'can_be_removed' => false,
        ]);

        Schema::enableForeignKeyConstraints();
    }
}
