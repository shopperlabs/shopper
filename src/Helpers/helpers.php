<?php

use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money;
use Shopper\Framework\Models\Order\Order;
use Shopper\Framework\Shopper;

if (!function_exists('app_name')) {
    /**
     * Helper to grab the application name.
     *
     * @return string
     */
    function app_name(): string
    {
        return config('app.name');
    }
}

if (! function_exists('gravatar')) {
    /**
     * Access the gravatar helper.
     *
     * @return mixed
     */
    function gravatar()
    {
        return app('gravatar');
    }
}

if (!function_exists('home_route')) {
    /**
     * Return the route to the "home" page depending on
     * authentication/authorization status.
     *
     * @return string
     */
    function home_route(): string
    {
        if (auth()->check()) {
            if (auth()->user()->can('view-backend')) {
                return 'shopper.dashboard';
            }

            return 'home';
        }
    }
}

if (!function_exists('setEnvironmentValue')) {
    /**
     * Function to set or update .env variable.
     *
     * @param array $values
     * @return bool
     */
    function setEnvironmentValue(array $values): bool
    {
        $envFile = app()->environmentFilePath();
        $str = file_get_contents($envFile);

        if (count($values) > 0) {
            $str .= "\n"; // In case the searched variable is in the last line without \n
            foreach ($values as $envKey => $envValue) {
                if ($envValue === true) {
                    $value = 'true';
                } elseif ($envValue === false) {
                    $value = 'false';
                } else {
                    $value = $envValue;
                }

                $envKey = strtoupper($envKey);
                $keyPosition = strpos($str, "{$envKey}=");
                $endOfLinePosition = strpos($str, "\n", $keyPosition);
                $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);
                $space = strpos($value, ' ');
                $envValue = ($space === false) ? $value : '"' . $value . '"';

                // If key does not exist, add it
                if (!$keyPosition || !$endOfLinePosition || !$oldLine) {
                    $str .= "{$envKey}={$envValue}\n";
                } else {
                    $str = str_replace($oldLine, "{$envKey}={$envValue}", $str);
                }
            }
        }

        $str = substr($str, 0, -1);

        if (!file_put_contents($envFile, $str)) {
            return false;
        }

        return true;
    }
}

if (!function_exists('shopper_version')) {
    /**
     * Function to return Shopper current version.
     *
     * @return string
     */
    function shopper_version(): string
    {
        return Shopper::version();
    }
}

if (!function_exists('shopper_table')) {
    /**
     * Return Shopper current table name.
     *
     * @param  string $table
     * @return string
     */
    function shopper_table(string $table): string
    {
        if (config('shopper.table_prefix') !== '') {
            return config('shopper.table_prefix').$table;
        }

        return $table;
    }
}

if (!function_exists('shopper_currency')) {
    /**
     * Return Shopper currency used.
     *
     * @return string
     */
    function shopper_currency(): string
    {
        return config('shopper.currency');
    }
}

if (!function_exists('shopper_prefix')) {
    /**
     * Return Shopper prefix used.
     *
     * @return string
     */
    function shopper_prefix(): string
    {
        return Shopper::prefix();
    }
}

if (!function_exists('shopper_money_format')) {
    /**
     * Return money format
     *
     * @param  mixed  $amount
     * @param  string|null  $currency
     * @return string
     */
    function shopper_money_format($amount, $currency = null): string
    {
        $money = new Money($amount, new Currency($currency ?? config('shopper.currency')));
        $currencies = new ISOCurrencies();

        $numberFormatter = new \NumberFormatter(app()->getLocale(), \NumberFormatter::CURRENCY);
        $moneyFormatter = new IntlMoneyFormatter($numberFormatter, $currencies);

        return $moneyFormatter->format($money);
    }
}

if (!function_exists('generate_number')) {
    /**
     * Generate Order Number.
     *
     * @return string
     */
    function generate_number(): string
    {
        $lastOrder = Order::orderBy('id', 'desc')->limit(1)->first();

        $generator = [
            'start_sequence_from' => 1,
            'prefix'              => '#',
            'pad_length'          => 1,
            'pad_string'          => '0'
        ];

        $last = $lastOrder ? $lastOrder->id : 0;
        $next = $generator['start_sequence_from'] + $last;

        return sprintf('%s%s', $generator['prefix'],
            str_pad($next, $generator['pad_length'], $generator['pad_string'], STR_PAD_LEFT)
        );
    }
}
