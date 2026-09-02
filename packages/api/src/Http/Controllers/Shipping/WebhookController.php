<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Controllers\Shipping;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Shopper\Shipping\Actions\ApplyTrackingInfoAction;
use Shopper\Shipping\Facades\Shipping;
use Throwable;

readonly class WebhookController
{
    public function __construct(
        private ApplyTrackingInfoAction $action,
    ) {}

    public function __invoke(Request $request, string $driver): JsonResponse
    {
        if (! in_array($driver, Shipping::availableDrivers(), strict: true) || ! Shipping::isConfigured($driver)) {
            abort(404);
        }

        $shippingDriver = Shipping::driver($driver);

        if (! $shippingDriver->supportsWebhooks()) {
            abort(404);
        }

        try {
            $info = $shippingDriver->handleWebhook($request);
        } catch (Throwable) {
            return response()->json(['error' => 'Invalid webhook payload.'], 400);
        }

        if ($info !== null) {
            $this->action->apply($driver, $info);
        }

        return response()->json(['received' => true]);
    }
}
