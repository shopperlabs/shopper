<?php

declare(strict_types=1);

namespace Shopper\Shipping\Actions;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Shopper\Core\Actions\RecordShipmentEventAction;
use Shopper\Core\Models\OrderShipping;
use Shopper\Shipping\DataTransferObjects\TrackingEvent;
use Shopper\Shipping\DataTransferObjects\TrackingInfo;

final class ApplyTrackingInfoAction
{
    public function __construct(
        private readonly RecordShipmentEventAction $recorder,
    ) {}

    public function apply(string $driver, TrackingInfo $info): ?OrderShipping
    {
        $shipment = $this->findShipment($driver, $info->trackingNumber);

        if ($shipment === null) {
            return null;
        }

        $this->applyTo($shipment, $info);

        return $shipment;
    }

    public function applyTo(OrderShipping $shipment, TrackingInfo $info): void
    {
        $events = collect($info->events)
            ->sortBy(fn (TrackingEvent $event): int => $event->occurredAt->getTimestamp())
            ->values();

        if ($events->isEmpty()) {
            $events = collect([new TrackingEvent(
                status: $info->status,
                description: $info->statusDescription,
                occurredAt: $info->deliveredAt ?? now(),
                externalId: 'status:'.$info->status->value,
            )]);
        }

        $identified = $events
            ->map(fn (TrackingEvent $event): array => [
                'external_id' => $event->externalId ?? $this->fingerprint($event),
                'event' => $event,
            ])
            ->unique('external_id')
            ->values();

        $known = $shipment->events()
            ->whereIn('external_id', $identified->pluck('external_id')->all())
            ->pluck('external_id')
            ->map(fn (mixed $externalId): string => (string) $externalId)
            ->all();

        $pending = $identified->reject(fn (array $entry): bool => in_array($entry['external_id'], $known, true));

        if ($pending->isEmpty()) {
            return;
        }

        $timezone = (string) config('app.timezone', 'UTC');

        foreach ($pending as ['external_id' => $externalId, 'event' => $event]) {
            $this->recorder->execute($shipment, $event->status, [
                'description' => $event->description,
                'location' => $event->location,
                'latitude' => $event->latitude,
                'longitude' => $event->longitude,
                'occurred_at' => Carbon::instance($event->occurredAt)->setTimezone($timezone),
                'external_id' => $externalId,
            ], fromCarrier: true);
        }
    }

    private function fingerprint(TrackingEvent $event): string
    {
        return sha1(implode('|', [
            $event->status->value,
            Carbon::instance($event->occurredAt)->utc()->toIso8601String(),
            $event->location ?? '',
        ]));
    }

    private function findShipment(string $driver, string $trackingNumber): ?OrderShipping
    {
        $candidates = OrderShipping::query()
            ->where('tracking_number', $trackingNumber)
            ->whereRelation('carrier', 'driver', $driver)
            ->latest('id')
            ->limit(5)
            ->get();

        if ($candidates->isEmpty()) {
            Log::warning('No shipment matches the carrier tracking number.', [
                'driver' => $driver,
                'tracking_number' => $trackingNumber,
            ]);

            return null;
        }

        $open = $candidates->reject(fn (OrderShipping $shipment): bool => $shipment->status?->isFinal() ?? false);

        if ($open->count() > 1) {
            Log::warning('Several open shipments share a carrier tracking number.', [
                'driver' => $driver,
                'tracking_number' => $trackingNumber,
                'shipment_ids' => $open->modelKeys(),
            ]);

            return null;
        }

        if ($open->count() === 1) {
            return $open->first();
        }

        if ($candidates->count() > 1) {
            Log::warning('Several closed shipments share a carrier tracking number.', [
                'driver' => $driver,
                'tracking_number' => $trackingNumber,
                'shipment_ids' => $candidates->modelKeys(),
            ]);

            return null;
        }

        return $candidates->first();
    }
}
