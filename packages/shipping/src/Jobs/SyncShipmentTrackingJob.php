<?php

declare(strict_types=1);

namespace Shopper\Shipping\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Shopper\Core\Models\OrderShipping;
use Shopper\Shipping\Actions\ApplyTrackingInfoAction;
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
        return (array) config('shopper.shipping.tracking.backoff', [60, 300, 900]);
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

        $action->applyTo($shipment, Shipping::driver($driver)->track($shipment->tracking_number));
    }
}
