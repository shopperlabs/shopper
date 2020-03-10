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
            'description' => __('Lorem ipsum, dolor sit amet consectetur adipisicing elit. Maiores impedit perferendis suscipit eaque, iste dolor cupiditate blanditiis ratione.'),
            'max_products' => 100
        ]);

        ShopSize::create([
            'name' => __('Medium'),
            'size' => __('>= 101 & <= 1000 Products'),
            'description' => __('Lorem ipsum, dolor sit amet consectetur adipisicing elit. Maiores impedit perferendis suscipit eaque, iste dolor cupiditate blanditiis ratione.'),
            'max_products' => 1000
        ]);

        ShopSize::create([
            'name' => __('Bigger'),
            'size' => __('>= 1001 Products'),
            'description' => __('Lorem ipsum, dolor sit amet consectetur adipisicing elit. Maiores impedit perferendis suscipit eaque, iste dolor cupiditate blanditiis ratione.'),
            'max_products' => 10000
        ]);

        $this->enableForeignKeys();
    }
}
