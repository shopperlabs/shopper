<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Controllers\Payment;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Shopper\Payment\Actions\IngestPaymentEvent;
use Shopper\Payment\Facades\Payment;
use Throwable;

final class WebhookController
{
    public function __construct(
        private readonly IngestPaymentEvent $ingest,
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

        $this->ingest->execute($driver, $result);

        return response()->json(['received' => true]);
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
