<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Shopper\Framework\Models\System\Currency;
use Shopper\Framework\Traits\Database\DisableForeignKeys;

class CurrenciesTableSeeder extends Seeder
{
    use DisableForeignKeys;

    protected array $currencies;

    public function __construct()
    {
        $this->currencies = include __DIR__ . '/currencies.php';
    }

    public function run(): void
    {
        $this->disableForeignKeys();

        foreach ($this->currencies as $code => $currency) {
            $data = array_merge($currency, ['code' => $code]);
            Currency::query()->create($data);
        }

        $this->enableForeignKeys();
    }
}
