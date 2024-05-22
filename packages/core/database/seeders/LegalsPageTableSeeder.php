<?php

declare(strict_types=1);

namespace Shopper\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Models\Legal;

final class LegalsPageTableSeeder extends Seeder
{
    public function run(): void
    {
        Schema::enableForeignKeyConstraints();

        Legal::query()->create([
            'title' => __('shopper::pages/settings/global.legal.refund'),
            'slug' => 'refund',
            'is_enabled' => true,
            'content' => null,
        ]);

        Legal::query()->create([
            'title' => __('shopper::pages/settings/global.legal.privacy'),
            'slug' => 'privacy',
            'is_enabled' => true,
            'content' => null,
        ]);

        Legal::query()->create([
            'title' => __('shopper::pages/settings/global.legal.terms_of_use'),
            'slug' => 'terms-of-use',
            'is_enabled' => true,
            'content' => null,
        ]);

        Legal::query()->create([
            'title' => __('shopper::pages/settings/global.legal.shipping'),
            'slug' => 'shipping',
            'is_enabled' => true,
            'content' => null,
        ]);

        Schema::enableForeignKeyConstraints();
    }
}
