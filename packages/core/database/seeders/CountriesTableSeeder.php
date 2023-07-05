<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Models\Country;

final class CountriesTableSeeder extends Seeder
{
    protected array $countries;

    public function __construct()
    {
        $this->countries = include __DIR__ . '/countries.php';
    }

    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        foreach ($this->countries as $key => $country) {
            Country::query()->create([
                'name' => $country['name']['common'],
                'name_official' => $country['name']['official'],
                'cca2' => $country['cca2'],
                'cca3' => $country['cca3'],
                'flag' => $country['flag'],
                'latitude' => $country['latlng'][0],
                'longitude' => $country['latlng'][1],
                'currencies' => $country['currencies'],
            ]);
        }

        Schema::enableForeignKeyConstraints();
    }
}
