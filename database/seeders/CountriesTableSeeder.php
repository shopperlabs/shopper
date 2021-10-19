<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Shopper\Framework\Models\System\Country;
use Shopper\Framework\Traits\Database\DisableForeignKeys;

class CountriesTableSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * All installable countries.
     *
     * @var array
     */
    protected $countries;

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        $this->countries = include __DIR__ . '/countries.php';
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeys();

        foreach ($this->countries as $key => $country) {
            Country::query()->create([
                'name'          => $country['name']['common'],
                'name_official' => $country['name']['official'],
                'cca2'          => $country['cca2'],
                'cca3'          => $country['cca3'],
                'flag'          => $country['flag'],
                'latitude'      => $country['latlng'][0],
                'longitude'     => $country['latlng'][1],
                'currencies'    => $country['currencies'],
            ]);
        }

        $this->enableForeignKeys();
    }
}
