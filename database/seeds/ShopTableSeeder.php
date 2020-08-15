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
            'size' => __('=< 100 Products'),
            'description' => __('You have just opened your store and you do not yet have a wide range of products. Start your activity slowly.'),
            'max_products' => 100
        ]);

        ShopSize::create([
            'name' => __('Medium'),
            'size' => __('>= 101 & <= 1000 Products'),
            'description' => __('You have a store that expands with a lot of product varieties, start with this plan and fill out all of your products.'),
            'max_products' => 1000
        ]);

        ShopSize::create([
            'name' => __('Bigger'),
            'size' => __('>= 1001 Products'),
            'description' => __('You have a business with employees that sells products and you want to boost your business.'),
            'max_products' => 10000
        ]);

        $this->enableForeignKeys();
    }
}
