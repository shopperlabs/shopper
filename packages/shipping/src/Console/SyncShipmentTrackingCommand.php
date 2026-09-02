<?php

declare(strict_types=1);

namespace Shopper\Shipping\Console;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Shopper\Core\Enum\ShipmentStatus;
use Shopper\Core\Models\OrderShipping;
use Shopper\Shipping\Facades\Shipping;
use Shopper\Shipping\Jobs\SyncShipmentTrackingJob;

final class SyncShipmentTrackingCommand extends Command
{
    protected $signature = 'shopper:shipments:sync-tracking';

    protected $description = 'Queue a tracking refresh for every undelivered shipment whose carrier supports tracking';

    public function handle(): int
    {
        $drivers = collect(Shipping::availableDrivers())
            ->filter(fn (string $driver): bool => Shipping::isConfigured($driver)
                && Shipping::driver($driver)->supportsTracking()
                && ! Shipping::driver($driver)->supportsWebhooks())
            ->values()
            ->all();

        if ($drivers === []) {
            $this->components->info('No configured carrier supports tracking.');

            return self::SUCCESS;
        }

        $queued = 0;
        $queue = config('shopper.shipping.tracking.queue');

        OrderShipping::query()
            ->whereNotNull('tracking_number')
            ->where(fn (Builder $query) => $query
                ->whereNull('status')
                ->orWhereIn('status', ShipmentStatus::open()))
            ->whereHas('carrier', fn (Builder $query) => $query->whereIn('driver', $drivers))
            ->chunkById(500, function (Collection $shipments) use (&$queued, $queue): void {
                $shipments->each(fn (OrderShipping $shipment) => SyncShipmentTrackingJob::dispatch($shipment->id)->onQueue($queue));

                $queued += $shipments->count();
            });

        $this->components->info("Queued {$queued} ".Str::plural('shipment', $queued).' for tracking sync.');

        return self::SUCCESS;
    }
}
