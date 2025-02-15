<?php

declare(strict_types=1);

namespace Shopper\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Shopper\Core\Models\Currency;

final class CurrenciesTableSeeder extends Seeder
{
    protected array $currencies;

    public function __construct()
    {
        $this->currencies = include __DIR__ . '/../data/currencies.php';
    }

    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        $currencies = collect($this->currencies)
            ->map(fn ($currency, $code): array => [
                'code' => $code,
                'name' => $currency['name'],
                'symbol' => $currency['symbol'],
                'format' => $currency['format'],
                'exchange_rate' => $currency['exchange_rate'],
                'is_enabled' => true,
            ])
            ->values()
            ->toArray();

        Currency::query()->insert($currencies);

        Schema::enableForeignKeyConstraints();
    }
}
