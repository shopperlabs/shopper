<?php

declare(strict_types=1);

namespace Shopper\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Shopper\Framework\Models\Shop\Legal;

class LegalsPageTableSeeder extends Seeder
{
    public function run(): void
    {
        Schema::enableForeignKeyConstraints();

        Legal::query()->create([
            'title' => $title = __('shopper::pages/settings.legal.refund'),
            'slug' => str_slug($title),
            'is_enabled' => true,
            'content' => null,
        ]);

        Legal::query()->create([
            'title' => $title = __('shopper::pages/settings.legal.privacy'),
            'slug' => str_slug($title),
            'is_enabled' => true,
            'content' => null,
        ]);

        Legal::query()->create([
            'title' => $title = __('shopper::pages/settings.legal.terms_of_use'),
            'slug' => str_slug($title),
            'is_enabled' => true,
            'content' => null,
        ]);

        Legal::query()->create([
            'title' => $title = __('shopper::pages/settings.legal.shipping'),
            'slug' => str_slug($title),
            'is_enabled' => true,
            'content' => null,
        ]);

        Schema::enableForeignKeyConstraints();
    }
}
