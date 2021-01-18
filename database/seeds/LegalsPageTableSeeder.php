<?php

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

        /**
         * Refund Policy.
         */
        Legal::query()->create([
            'title' => $title = 'Refund policy',
            'slug' => str_slug($title),
            'is_enabled' => true,
            'content' => null
        ]);

        /**
         * Privacy Policy.
         */
        Legal::query()->create([
            'title' => $title = 'Privacy policy',
            'slug' => str_slug($title),
            'is_enabled' => true,
            'content' => null
        ]);

        /**
         * Terms of uses.
         */
        Legal::query()->create([
            'title' => $title = 'Terms of uses',
            'slug' => str_slug($title),
            'is_enabled' => true,
            'content' => null
        ]);

        /**
         * Terms of uses.
         */
        Legal::query()->create([
            'title' => $title = 'Shipping policy',
            'slug' => str_slug($title),
            'is_enabled' => true,
            'content' => null
        ]);

        $this->enableForeignKeys();
    }
}
