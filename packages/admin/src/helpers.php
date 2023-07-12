<?php

declare(strict_types=1);

if (! function_exists('active')) {
    function active(array $routes, string $activeClass = 'active', string $defaultClass = '', bool $condition = true): string
    {
        return call_user_func_array([app('router'), 'is'], $routes) && $condition ? $activeClass : $defaultClass;
    }
}

if (! function_exists('is_active')) {
    function is_active(array $routes): bool
    {
        return (bool) call_user_func_array([app('router'), 'is'], $routes);
    }
}

if (! function_exists('isoToEmoji')) {
    function isoToEmoji(string $code): string
    {
        return implode(
            '',
            array_map(
                fn (string $letter) => mb_chr(ord($letter) % 32 + 0x1F1E5),
                str_split($code)
            )
        );
    }
}
