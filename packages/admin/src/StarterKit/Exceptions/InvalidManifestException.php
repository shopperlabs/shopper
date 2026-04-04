<?php

declare(strict_types=1);

namespace Shopper\StarterKit\Exceptions;

use RuntimeException;

final class InvalidManifestException extends RuntimeException
{
    public static function missingField(string $field): self
    {
        return new self("The starter kit manifest is missing the required field: [{$field}].");
    }

    public static function invalidYaml(string $reason): self
    {
        return new self("The starter kit manifest contains invalid YAML: {$reason}");
    }
}
