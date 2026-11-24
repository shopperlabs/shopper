<?php

declare(strict_types=1);

namespace Shopper\Core\Exceptions;

use RuntimeException;

final class ProductImportException extends RuntimeException
{
    public ?string $field = null;

    public static function invalidNumber(string $handle, string $field, string $column, string $value): self
    {
        return self::invalidCell('invalid_number', $handle, $field, $column, $value);
    }

    public static function invalidBoolean(string $handle, string $field, string $column, string $value): self
    {
        return self::invalidCell('invalid_boolean', $handle, $field, $column, $value);
    }

    private static function invalidCell(string $reason, string $handle, string $field, string $column, string $value): self
    {
        $exception = new self(__("shopper-core::exceptions.import.{$reason}", [
            'handle' => $handle,
            'column' => $column,
            'value' => $value,
        ]));

        $exception->field = $field;

        return $exception;
    }
}
