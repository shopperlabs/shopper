<?php

use Illuminate\Database\Seeder;
use Shopper\Framework\Models\Shop\ShopSize;
use Shopper\Framework\Traits\Database\DisableForeignKeys;

class ShopTableSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seed.
     */
    public function run()
    {
        $this->disableForeignKeys();

        ShopSize::create([
            'name' => __('Small'),
            'description' => __('=< 100 Products'),
            'max_products' => 100
        ]);

        ShopSize::create([
            'name' => __('Medium'),
            'description' => __('>= 101 & <= 1000 Products'),
            'max_products' => 1000
        ]);

        ShopSize::create([
            'name' => __('Bigger'),
            'description' => __('>= 1001 Products'),
            'max_products' => 10000
        ]);

        $this->enableForeignKeys();
    }
}
