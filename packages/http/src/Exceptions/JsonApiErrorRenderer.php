<?php

declare(strict_types=1);

namespace Shopper\Http\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Shopper\Http\Enum\ErrorCode;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

final class JsonApiErrorRenderer
{
    public function render(Throwable $exception, Request $request): ?JsonResponse
    {
        if (! $this->handlesRequest($request)) {
            return null;
        }

        if ($exception instanceof ValidationException) {
            return $this->response($exception->status, $this->validationErrors($exception));
        }

        $status = match (true) {
            $exception instanceof AuthenticationException => Response::HTTP_UNAUTHORIZED,
            $exception instanceof HttpExceptionInterface => $exception->getStatusCode(),
            default => Response::HTTP_INTERNAL_SERVER_ERROR,
        };

        return $this->response(
            $status,
            [[
                'status' => (string) $status,
                'code' => $this->code($exception, $status)->value,
                'title' => $this->title($status),
                'detail' => $this->detail($exception, $status),
            ]],
            $exception instanceof HttpExceptionInterface ? $exception->getHeaders() : [],
        );
    }

    private function handlesRequest(Request $request): bool
    {
        $prefix = mb_trim((string) config('shopper.http.prefix', 'store'), '/');

        return $prefix !== '' && $request->is($prefix.'/*');
    }

    private function code(Throwable $exception, int $status): ErrorCode
    {
        return $exception instanceof ApiException
            ? $exception->errorCode
            : ErrorCode::fromStatus($status);
    }

    /**
     * The message of an HTTP exception is written for the client
     */
    private function detail(Throwable $exception, int $status): string
    {
        if ($exception instanceof AuthenticationException) {
            return $exception->getMessage();
        }

        if (
            $exception instanceof HttpExceptionInterface
            && $exception->getMessage() !== ''
            && ! $exception->getPrevious() instanceof ModelNotFoundException
        ) {
            return $exception->getMessage();
        }

        return $this->title($status);
    }

    /**
     * @param  array<int, array<string, mixed>>  $errors
     * @param  array<string, string>  $headers
     */
    private function response(int $status, array $errors, array $headers = []): JsonResponse
    {
        return new JsonResponse(
            data: ['errors' => $errors],
            status: $status,
            headers: [...$headers, 'Content-Type' => 'application/vnd.api+json'],
        );
    }

    /**
     * One error object per message
     *
     * @return array<int, array<string, mixed>>
     */
    private function validationErrors(ValidationException $exception): array
    {
        $errors = [];
        $failed = $exception->validator->failed();

        foreach ($exception->errors() as $field => $messages) {
            $rules = array_keys($failed[$field] ?? []);

            foreach (array_values($messages) as $index => $message) {
                $errors[] = [
                    'status' => (string) $exception->status,
                    'code' => $this->validationCode($exception, count($rules) === 1 ? $rules[0] : ($rules[$index] ?? null)),
                    'title' => $this->title($exception->status),
                    'detail' => $message,
                    'source' => ['pointer' => '/data/attributes/'.str_replace('.', '/', $field)],
                ];
            }
        }

        return $errors;
    }

    private function validationCode(ValidationException $exception, ?string $rule): string
    {
        if ($exception instanceof ApiValidationException) {
            return $exception->errorCode->value;
        }

        return $rule === null
            ? ErrorCode::Invalid->value
            : Str::snake(class_basename($rule));
    }

    private function title(int $status): string
    {
        return Response::$statusTexts[$status] ?? 'Error';
    }
}
