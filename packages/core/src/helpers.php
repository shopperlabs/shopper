<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money;
use Shopper\Core\Models\Currency as ModelCurrency;
use Shopper\Core\Models\Order;
use Shopper\Core\Models\Setting;
use Shopper\Core\Shopper;

if (! function_exists('generate_number')) {
    function generate_number(): string
    {
        $lastOrder = Order::query()->orderBy('id', 'desc')->limit(1)->first();

        $generator = [
            'start_sequence_from' => 1,
            'prefix' => '#',
            'pad_length' => 1,
            'pad_string' => '0',
        ];

        $last = $lastOrder ? $lastOrder->id : 0;
        $next = $generator['start_sequence_from'] + $last;

        return sprintf(
            '%s%s',
            $generator['prefix'],
            str_pad((string) $next, $generator['pad_length'], $generator['pad_string'], \STR_PAD_LEFT)
        );
    }
}

if (! function_exists('setEnvironmentValue')) {
    function setEnvironmentValue(array $values): bool
    {
        $envFile = app()->environmentFilePath();
        $str = file_get_contents($envFile);

        if (\count($values) > 0) {
            $str .= "\n"; // In case the searched variable is in the last line without \n
            foreach ($values as $envKey => $envValue) {
                if ($envValue === true) {
                    $value = 'true';
                } elseif ($envValue === false) {
                    $value = 'false';
                } else {
                    $value = $envValue;
                }

                $envKey = mb_strtoupper($envKey);
                $keyPosition = mb_strpos($str, "{$envKey}=");
                $endOfLinePosition = mb_strpos($str, "\n", $keyPosition);
                $oldLine = mb_substr($str, $keyPosition, $endOfLinePosition - $keyPosition);
                $space = mb_strpos($value, ' ');
                $envValue = $space === false ? $value : '"' . $value . '"';

                // If key does not exist, add it
                if (! $keyPosition || ! $endOfLinePosition || ! $oldLine) {
                    $str .= "{$envKey}={$envValue}\n";
                } else {
                    $str = str_replace($oldLine, "{$envKey}={$envValue}", $str);
                }
                env($envKey, $envValue);
            }
        }

        $str = mb_substr($str, 0, -1);

        if (! file_put_contents($envFile, $str)) {
            return false;
        }

        return true;
    }
}

if (! function_exists('shopper_version')) {
    function shopper_version(): string
    {
        return Shopper::version();
    }
}

if (! function_exists('shopper_table')) {
    function shopper_table(string $table): string
    {
        if (config('shopper.core.table_prefix') !== '') {
            return config('shopper.core.table_prefix') . $table;
        }

        return $table;
    }
}

if (! function_exists('shopper_asset')) {
    function shopper_asset(string $file): string
    {
        return Storage::disk(config('shopper.core.storage.disk_name'))->url($file);
    }
}

if (! function_exists('shopper_currency')) {
    function shopper_currency(): string
    {
        $settingCurrency = shopper_setting('shop_currency_id');

        if ($settingCurrency) {
            $currency = Cache::remember(
                'shopper-currency',
                now()->addHour(),
                fn () => ModelCurrency::query()->find($settingCurrency)
            );

            return $currency ? $currency->code : 'USD'; // @phpstan-ignore-line
        }

        return 'USD';
    }
}

if (! function_exists('shopper_money_format')) {
    function shopper_money_format($amount, ?string $currency = null): string
    {
        $money = new Money($amount, new Currency($currency ?? shopper_currency()));
        $currencies = new ISOCurrencies();

        $numberFormatter = new \NumberFormatter(app()->getLocale(), \NumberFormatter::CURRENCY);
        $moneyFormatter = new IntlMoneyFormatter($numberFormatter, $currencies);

        return $moneyFormatter->format($money);
    }
}

if (! function_exists('shopper_prefix')) {
    function shopper_prefix(): string
    {
        return Shopper::prefix();
    }
}

if (! function_exists('shopper_setting')) {
    function shopper_setting(string $key): mixed
    {
        $setting = Cache::remember(
            "shopper-setting-{$key}",
            3600 * 24,
            fn () => Setting::query()->where('key', $key)->first()
        );

        return $setting?->value; // @phpstan-ignore-line
    }
}

if (! function_exists('useTryCatch')) {
    function useTryCatch(Closure $closure): array
    {
        $result = null;
        $throwable = null;

        try {
            $result = $closure();
        } catch (Throwable $exception) {
            $throwable = $exception;
        }

        return [$throwable, $result];
    }
}
