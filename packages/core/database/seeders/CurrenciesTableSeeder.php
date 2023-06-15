<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Models\Currency;

class CurrenciesTableSeeder extends Seeder
{
    protected array $currencies;

    public function __construct()
    {
        $this->currencies = include __DIR__ . '/currencies.php';
    }

    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        foreach ($this->currencies as $code => $currency) {
            $data = array_merge($currency, ['code' => $code]);
            Currency::query()->create($data);
        }

        Schema::enableForeignKeyConstraints();
    }
}
