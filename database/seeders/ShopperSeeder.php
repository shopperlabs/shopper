<?php

namespace Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class ShopperSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(AuthTableSeeder::class);
        $this->call(CountriesTableSeeder::class);
        $this->call(CurrenciesTableSeeder::class);
        $this->call(LegalsPageTableSeeder::class);

        Model::reguard();
    }
}
