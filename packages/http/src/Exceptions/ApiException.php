<?php

declare(strict_types=1);

namespace Shopper\Http\Exceptions;

use Shopper\Http\Enum\ErrorCode;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class ApiException extends HttpException
{
    /**
     * @param  array<string, string|int>  $headers
     */
    public function __construct(
        int $status,
        public readonly ErrorCode $errorCode,
        string $message = '',
        array $headers = [],
    ) {
        parent::__construct($status, $message, null, $headers);
    }
}
