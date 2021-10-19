<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Shopper\Framework\Models\Shop\Legal;
use Shopper\Framework\Traits\Database\DisableForeignKeys;

class LegalsPageTableSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seed.
     */
    public function run()
    {
        $this->disableForeignKeys();

        /*
         * Refund Policy.
         */
        Legal::query()->create([
            'title' => $title = __('Refund policy'),
            'slug' => str_slug($title),
            'is_enabled' => true,
            'content' => null,
        ]);

        /*
         * Privacy Policy.
         */
        Legal::query()->create([
            'title' => $title = __('Privacy policy'),
            'slug' => str_slug($title),
            'is_enabled' => true,
            'content' => null,
        ]);

        /*
         * Terms of uses.
         */
        Legal::query()->create([
            'title' => $title = __('Terms of use'),
            'slug' => str_slug($title),
            'is_enabled' => true,
            'content' => null,
        ]);

        /*
         * Terms of uses.
         */
        Legal::query()->create([
            'title' => $title = __('Shipping policy'),
            'slug' => str_slug($title),
            'is_enabled' => true,
            'content' => null,
        ]);

        $this->enableForeignKeys();
    }
}
