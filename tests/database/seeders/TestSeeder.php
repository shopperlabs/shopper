<?php

declare(strict_types=1);

namespace Tests\Database\Seeders;

use Illuminate\Database\Seeder;
use Shopper\Core\Database\Seeders\ShopperSeeder;
use Shopper\Database\Seeders\AuthTableSeeder;

final class TestSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(ShopperSeeder::class);
        $this->call(AuthTableSeeder::class);
    }
}
