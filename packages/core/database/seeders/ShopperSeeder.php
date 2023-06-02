<?php

declare(strict_types=1);

namespace Shopper\Core\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class ShopperSeeder extends Seeder
{
    public function run(): void
    {
        Model::unguard();

        $this->call(AuthTableSeeder::class);
        $this->call(CountriesTableSeeder::class);
        $this->call(CurrenciesTableSeeder::class);
        $this->call(LegalsPageTableSeeder::class);

        Model::reguard();
    }
}
