<?php

declare(strict_types=1);

namespace Shopper\Http\Exceptions;

use Shopper\Http\Enum\ErrorCode;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class ApiException extends HttpException
{
    public function __construct(
        int $status,
        public readonly ErrorCode $errorCode,
        string $message = '',
    ) {
        parent::__construct($status, $message);
    }
}
