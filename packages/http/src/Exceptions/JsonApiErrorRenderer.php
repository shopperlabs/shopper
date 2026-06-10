<?php

declare(strict_types=1);

namespace Shopper\Http\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

/**
 * Renders exceptions thrown on Shopper API routes as JSON:API error documents
 * ({ "errors": [...] }) with the application/vnd.api+json content type, so the
 * error contract matches the success contract. Returns null for non-API routes,
 * letting the default handler take over.
 */
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
            $exception instanceof AuthorizationException => Response::HTTP_FORBIDDEN,
            $exception instanceof ModelNotFoundException => Response::HTTP_NOT_FOUND,
            $exception instanceof HttpExceptionInterface => $exception->getStatusCode(),
            default => Response::HTTP_INTERNAL_SERVER_ERROR,
        };

        $detail = $exception instanceof HttpExceptionInterface && $exception->getMessage() !== ''
            ? $exception->getMessage()
            : $this->title($status);

        return $this->response($status, [[
            'status' => (string) $status,
            'title' => $this->title($status),
            'detail' => $detail,
        ]]);
    }

    private function handlesRequest(Request $request): bool
    {
        $prefix = mb_trim((string) config('shopper.http.prefix', 'store'), '/');

        return $prefix !== '' && $request->is($prefix.'/*');
    }

    /**
     * @param  array<int, array<string, mixed>>  $errors
     */
    private function response(int $status, array $errors): JsonResponse
    {
        return new JsonResponse(
            data: ['errors' => $errors],
            status: $status,
            headers: ['Content-Type' => 'application/vnd.api+json'],
        );
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function validationErrors(ValidationException $exception): array
    {
        $errors = [];

        foreach ($exception->errors() as $field => $messages) {
            foreach ($messages as $message) {
                $errors[] = [
                    'status' => (string) $exception->status,
                    'title' => $this->title($exception->status),
                    'detail' => $message,
                    'source' => ['pointer' => '/data/attributes/'.$field],
                ];
            }
        }

        return $errors;
    }

    private function title(int $status): string
    {
        return Response::$statusTexts[$status] ?? 'Error';
    }
}
