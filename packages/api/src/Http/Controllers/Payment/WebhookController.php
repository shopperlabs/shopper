<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Controllers\Payment;

use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Shopper\Payment\DataTransferObjects\WebhookResult;
use Shopper\Payment\Facades\Payment;
use Shopper\Payment\Models\PaymentWebhookEvent;
use Shopper\Payment\Services\PaymentProcessingService;
use Throwable;

final class WebhookController
{
    public function __construct(
        private readonly PaymentProcessingService $service,
    ) {}

    public function __invoke(Request $request, string $driver): JsonResponse
    {
        if (! in_array($driver, Payment::availableDrivers(), strict: true)) {
            abort(404);
        }

        try {
            $result = Payment::driver($driver)->handleWebhook(
                [...$request->all(), '_raw_body' => $request->getContent()],
                $this->headers($request),
            );
        } catch (Throwable) {
            return response()->json(['error' => 'Invalid webhook payload.'], 400);
        }

        if ($result->isIgnored()) {
            return response()->json(['received' => true]);
        }

        if (! $this->firstDelivery($driver, $result)) {
            return response()->json(['received' => true]);
        }

        $this->service->processWebhook($driver, $result);

        return response()->json(['received' => true]);
    }

    /**
     * Record the event id once. A duplicate delivery hits the unique constraint
     * and reports false so the caller can acknowledge without reprocessing.
     */
    private function firstDelivery(string $driver, WebhookResult $result): bool
    {
        if ($result->eventId === null) {
            return true;
        }

        try {
            PaymentWebhookEvent::query()->create([
                'driver' => $driver,
                'event_id' => $result->eventId,
                'type' => $result->action,
                'payload' => $result->toArray(),
                'processed_at' => now(),
            ]);
        } catch (QueryException) {
            return false;
        }

        return true;
    }

    /**
     * @return array<string, string>
     */
    private function headers(Request $request): array
    {
        return array_map(
            static fn (array $values): string => (string) ($values[0] ?? ''),
            $request->headers->all(),
        );
    }
}
