<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Controllers\Payment;

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
        if (! in_array($driver, Payment::availableDrivers(), strict: true) || ! Payment::isConfigured($driver)) {
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
     * Record the event id once. A duplicate delivery is ignored at the database
     * level and reports false so the caller can acknowledge without
     * reprocessing. insertOrIgnore avoids the failed-INSERT that would abort the
     * surrounding transaction on Postgres.
     */
    private function firstDelivery(string $driver, WebhookResult $result): bool
    {
        if ($result->eventId === null) {
            return true;
        }

        $now = now();

        $inserted = PaymentWebhookEvent::query()->insertOrIgnore([
            'driver' => $driver,
            'event_id' => $result->eventId,
            'type' => $result->action,
            'payload' => json_encode($result->toArray()),
            'processed_at' => $now,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        return $inserted > 0;
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
