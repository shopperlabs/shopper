<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Shopper\Framework\Models\System\Currency;
use Shopper\Framework\Traits\Database\DisableForeignKeys;

class CurrenciesTableSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * All installable currencies.
     *
     * @var array
     */
    protected $currencies;

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        $this->currencies = include __DIR__ . '/currencies.php';
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeys();

        foreach ($this->currencies as $code => $currency) {
            $data = array_merge($currency, ['code' => $code]);
            Currency::query()->create($data);
        }

        $this->enableForeignKeys();
    }
}
