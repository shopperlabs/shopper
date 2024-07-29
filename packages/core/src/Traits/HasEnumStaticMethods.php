<?php

declare(strict_types=1);

namespace Shopper\Core\Traits;

use BackedEnum;
use Shopper\Core\Exceptions\UndefinedEnumCaseError;

/**
 * @mixin BackedEnum
 */
trait HasEnumStaticMethods
{
    public function __invoke(): int | string
    {
        return $this->value;
    }

    public static function __callStatic(string $name, mixed $args): int | string
    {
        $cases = self::cases();

        foreach ($cases as $case) {
            if ($case->name === $name) {
                return $case->value;
            }
        }

        throw new UndefinedEnumCaseError(
            enum: self::class,
            case: $name,
        );
    }
}
