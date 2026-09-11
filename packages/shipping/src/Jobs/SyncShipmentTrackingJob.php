<?php

declare(strict_types=1);

namespace Shopper\Shipping\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Shopper\Core\Models\OrderShipping;
use Shopper\Shipping\Actions\ApplyTrackingInfoAction;
use Shopper\Shipping\Exceptions\TrackingNotFoundException;
use Shopper\Shipping\Facades\Shipping;

final class SyncShipmentTrackingJob implements ShouldBeUnique, ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public int $uniqueFor = 3600;

    public function __construct(
        private readonly int $shipmentId,
    ) {}

    public function uniqueId(): string
    {
        return (string) $this->shipmentId;
    }

    /**
     * @return array<int, int>
     */
    public function backoff(): array
    {
        $backoff = Arr::wrap(config('shopper.shipping.tracking.backoff') ?? [60, 300, 900]);

        return array_values(array_map('intval', $backoff));
    }

    public function tries(): int
    {
        return count($this->backoff()) + 1;
    }

    public function handle(ApplyTrackingInfoAction $action): void
    {
        $shipment = OrderShipping::query()->with('carrier')->find($this->shipmentId);

        if ($shipment === null
            || $shipment->tracking_number === null
            || $shipment->status?->isFinal()) {
            return;
        }

        $driver = $shipment->carrier?->driver;

        if ($driver === null || ! Shipping::isConfigured($driver) || ! Shipping::driver($driver)->supportsTracking()) {
            return;
        }

        try {
            $tracking = Shipping::driver($driver)->track($shipment->tracking_number);
        } catch (TrackingNotFoundException) {
            Log::warning('The carrier has no record of this tracking number.', [
                'shipment_id' => $shipment->id,
                'driver' => $driver,
                'tracking_number' => $shipment->tracking_number,
            ]);

            return;
        }

        $action->applyTo($shipment, $tracking);
    }
}
