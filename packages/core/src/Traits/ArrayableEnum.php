<?php

declare(strict_types=1);

namespace Shopper\Core\Traits;

trait ArrayableEnum
{
    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function toArray(): array
    {
        return array_combine(self::values(), self::names());
    }

    public static function options(): array
    {
        if (method_exists(self::class, 'label')) {
            return collect(self::cases())
                ->mapWithKeys(fn (self $enum) => [
                    $enum->value => $enum->label(),
                ])
                ->toArray();
        }

        return [];
    }
}
