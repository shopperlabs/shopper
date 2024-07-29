<?php

declare(strict_types=1);

namespace Shopper\Core\Exceptions;

use Error;

final class UndefinedEnumCaseError extends Error
{
    /**
     * @param  class-string  $enum
     * @return void
     */
    public function __construct(string $enum, string $case)
    {
        parent::__construct(
            message: "Undefined constant {$enum}::{$case}.",
        );
    }
}
