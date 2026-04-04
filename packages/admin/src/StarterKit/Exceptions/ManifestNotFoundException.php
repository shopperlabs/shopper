<?php

declare(strict_types=1);

namespace Shopper\StarterKit\Exceptions;

use RuntimeException;

final class ManifestNotFoundException extends RuntimeException
{
    public static function atPath(string $path): self
    {
        return new self("The starter kit manifest was not found at [{$path}]. This package is not a valid Shopper starter kit.");
    }
}
