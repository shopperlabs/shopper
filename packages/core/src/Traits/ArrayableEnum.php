<?php

declare(strict_types=1);

namespace Shopper\Core\Traits;

use BackedEnum;
use Shopper\Core\Contracts\HasLabel;

trait ArrayableEnum
{
    /**
     * @return array<array-key, string>
     */
    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    /**
     * @return array<array-key, string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * @return array<array-key, string>
     */
    public static function toArray(): array
    {
        return array_combine(self::values(), self::names());
    }

    /**
     * @return array<string, mixed>
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (BackedEnum&HasLabel $enum): array => [
                $enum->value => $enum->getLabel(),
            ])
            ->toArray();
    }
}
