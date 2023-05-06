<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money;
use Shopper\Framework\Models\Shop\Order\Order;
use Shopper\Framework\Models\System\Currency as CurrencyModel;
use Shopper\Framework\Models\System\Setting;
use Shopper\Framework\Shopper;

if (! \function_exists('app_name')) {
    /**
     * Helper to grab the application name.
     */
    function app_name(): string
    {
        return config('app.name');
    }
}

if (! \function_exists('generate_number')) {
    /**
     * Generate Order Number.
     */
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

if (! \function_exists('setEnvironmentValue')) {
    /**
     * Function to set or update .env variable.
     */
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
                $keyPosition = (int) mb_strpos($str, "{$envKey}=");
                $endOfLinePosition = (int) mb_strpos($str, "\n", $keyPosition);
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

if (! \function_exists('shopper_version')) {
    /**
     * Function to return Shopper current version.
     */
    function shopper_version(): string
    {
        return Shopper::version();
    }
}

if (! \function_exists('shopper_table')) {
    /**
     * Return Shopper current table name.
     */
    function shopper_table(string $table): string
    {
        if (config('shopper.system.table_prefix') !== '') {
            return config('shopper.system.table_prefix') . $table;
        }

        return $table;
    }
}

if (! \function_exists('shopper_currency')) {
    /**
     * Return Shopper currency used.
     */
    function shopper_currency(): string
    {
        $settingCurrency = shopper_setting('shop_currency_id');

        if ($settingCurrency) {
            $currency = Cache::remember('shopper-currency', now()->addHour(), fn () => CurrencyModel::query()->find($settingCurrency));

            return $currency ? $currency->code : 'USD';
        }

        return 'USD';
    }
}

if (! \function_exists('shopper_money_format')) {
    /**
     * Return money format.
     */
    function shopper_money_format($amount, ?string $currency = null): string
    {
        $money = new Money($amount, new Currency($currency ?? shopper_currency()));
        $currencies = new ISOCurrencies();

        $numberFormatter = new \NumberFormatter(app()->getLocale(), \NumberFormatter::CURRENCY);
        $moneyFormatter = new IntlMoneyFormatter($numberFormatter, $currencies);

        return $moneyFormatter->format($money);
    }
}

if (! \function_exists('shopper_prefix')) {
    /**
     * Return Shopper prefix used.
     */
    function shopper_prefix(): string
    {
        return Shopper::prefix();
    }
}

if (! \function_exists('shopper_asset')) {
    /**
     * Return the full path of an image.
     */
    function shopper_asset(string $file, string $disk = 'uploads'): string
    {
        return Storage::disk(config('shopper.system.storage.disks.' . $disk))->url($file);
    }
}

if (! \function_exists('shopper_setting')) {
    /**
     * Return shopper setting from the setting table.
     */
    function shopper_setting(string $key): mixed
    {
        $setting = Cache::remember("shopper-setting-{$key}", 60 * 60 * 24, fn () => Setting::query()->where('key', $key)->first());

        return $setting?->value;
    }
}

if (! function_exists('active')) {
    /**
     * Sets the menu item class for an active route.
     */
    function active($routes, string $activeClass = 'active', string $defaultClass = '', bool $condition = true): string
    {
        return call_user_func_array([app('router'), 'is'], (array) $routes) && $condition ? $activeClass : $defaultClass;
    }
}

if (! function_exists('is_active')) {
    /**
     * Determines if the given routes are active.
     */
    function is_active($routes): bool
    {
        return (bool) call_user_func_array([app('router'), 'is'], (array) $routes);
    }
}
