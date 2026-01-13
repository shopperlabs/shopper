<?php

declare(strict_types=1);

namespace Shopper\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Models\Role;

final class RolesTableSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        Role::create([
            'name' => config('shopper.core.roles.admin'),
            'display_name' => __('shopper-core::roles.admin.display_name'),
            'description' => __('shopper-core::roles.admin.description'),
            'can_be_removed' => false,
        ]);

        Role::create([
            'name' => config('shopper.core.roles.manager'),
            'display_name' => __('shopper-core::roles.manager.display_name'),
            'description' => __('shopper-core::roles.manager.description'),
            'can_be_removed' => false,
        ]);

        Role::create([
            'name' => config('shopper.core.roles.user'),
            'display_name' => __('shopper-core::roles.user.display_name'),
            'description' => __('shopper-core::roles.user.description'),
            'can_be_removed' => false,
        ]);

        Schema::enableForeignKeyConstraints();
    }
}
