<?php

declare(strict_types=1);

use Akaunting\Money;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Shopper\Core\Models\Currency;
use Shopper\Core\Models\Order;
use Shopper\Core\Models\Setting;

if (! function_exists('generate_number')) {
    function generate_number(): string
    {
        $lastOrder = Order::query()->orderBy('id', 'desc')
            ->limit(1)
            ->first();

        $generator = config('shopper.orders.generator');

        $last = $lastOrder ? $lastOrder->id : 0;
        $next = $generator['start_sequence_from'] + $last;

        return sprintf(
            '%s%s',
            $generator['prefix'],
            mb_str_pad((string) $next, $generator['pad_length'], $generator['pad_string'], \STR_PAD_LEFT)
        );
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
        $currencyId = shopper_setting('default_currency_id');

        if ($currencyId) {
            $currency = Cache::remember(
                'shopper-currency',
                now()->addHour(),
                fn () => Currency::query()->find($currencyId)
            );

            return $currency ? $currency->code : 'USD';
        }

        return 'USD';
    }
}

if (! function_exists('shopper_money_format')) {
    function shopper_money_format(int | float $amount, ?string $currency = null, bool $convert = false): string
    {
        $money = new Money\Money(
            amount: $amount,
            currency: new Money\Currency($currency ?? shopper_currency()),
            convert: $convert
        );

        return $money->formatLocale(app()->getLocale());
    }
}

if (! function_exists('shopper_setting')) {
    function shopper_setting(string $key, bool $withCache = true): mixed
    {
        $setting = Cache::remember(
            "shopper-setting-{$key}",
            $withCache ? 3600 * 24 : 1,
            fn () => Setting::query()->where('key', $key)->first()
        );

        return $setting?->value;
    }
}

if (! function_exists('useTryCatch')) {
    function useTryCatch(Closure $closure, ?Closure $catchable = null): array
    {
        $result = null;
        $throwable = null;

        $catch = $catchable ?? fn (Throwable $exception) => $exception;

        try {
            $result = $closure();
        } catch (Throwable $exception) {
            $throwable = $catch($exception);
        }

        return [$throwable, $result];
    }
}

if (! function_exists('is_no_division_currency')) {
    function is_no_division_currency(string $currency): bool
    {
        return in_array($currency, [
            'BIF',
            'CLP',
            'DJF',
            'GNF',
            'HTG',
            'JPY',
            'KMF',
            'KRW',
            'MGA',
            'PYG',
            'RWF',
            'VND',
            'VUV',
            'XAF',
            'XAG',
            'XAU',
            'XDR',
            'XOF',
            'XPF',
        ]);
    }
}
