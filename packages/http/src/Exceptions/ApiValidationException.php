<?php

declare(strict_types=1);

namespace Shopper\Http\Exceptions;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Shopper\Http\Enum\ErrorCode;

final class ApiValidationException extends ValidationException
{
    /**
     * @param  array<string, mixed>  $meta
     */
    public function __construct(
        Validator $validator,
        public readonly ErrorCode $errorCode = ErrorCode::Invalid,
        public readonly array $meta = [],
    ) {
        parent::__construct($validator);
    }

    /**
     * @param  array<string, string|array<int, string>>  $messages
     * @param  array<string, mixed>  $meta
     */
    public static function withCode(ErrorCode $code, array $messages, array $meta = []): self
    {
        return new self(ValidationException::withMessages($messages)->validator, $code, $meta);
    }
}
