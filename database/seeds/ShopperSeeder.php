<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Shopper\Framework\Traits\Database\Seedable;

class ShopperSeeder extends Seeder
{
    use Seedable;

    protected $seedersPath = __DIR__.'/';

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->seed('AuthTableSeeder');
        $this->seed('ShopTableSeeder');

        Model::reguard();
    }
}
