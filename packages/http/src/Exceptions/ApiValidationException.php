<?php

declare(strict_types=1);

namespace Shopper\Http\Exceptions;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Shopper\Http\Enum\ErrorCode;

final class ApiValidationException extends ValidationException
{
    public function __construct(
        Validator $validator,
        public readonly ErrorCode $errorCode = ErrorCode::Invalid,
    ) {
        parent::__construct($validator);
    }

    /**
     * @param  array<string, string|array<int, string>>  $messages
     */
    public static function withCode(ErrorCode $code, array $messages): self
    {
        return new self(ValidationException::withMessages($messages)->validator, $code);
    }
}
