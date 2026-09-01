<?php

declare(strict_types=1);

namespace Shopper\Core\Actions;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Shopper\Core\Enum\FulfillmentStatus;
use Shopper\Core\Enum\OrderStatus;
use Shopper\Core\Enum\ShipmentEventSource;
use Shopper\Core\Enum\ShipmentStatus;
use Shopper\Core\Events\Orders\OrderShipmentDelivered;
use Shopper\Core\Events\Orders\OrderShipmentDeliveryFailed;
use Shopper\Core\Events\Orders\OrderShipmentEventRecorded;
use Shopper\Core\Events\Orders\OrderShipmentReturned;
use Shopper\Core\Models\OrderShipping;
use Shopper\Core\Models\OrderShippingEvent;

final class RecordShipmentEventAction
{
    /**
     * @param  array{
     *     description?: string|null,
     *     location?: string|null,
     *     latitude?: float|null,
     *     longitude?: float|null,
     *     occurred_at?: \Carbon\CarbonInterface,
     *     external_id?: string|null,
     *     causer_id?: int|null,
     *     metadata?: array<string, mixed>,
     * }  $context
     */
    public function execute(
        OrderShipping $shipment,
        ShipmentStatus $status,
        array $context = [],
        bool $fromCarrier = false,
    ): ?OrderShippingEvent {
        $context['source'] = $fromCarrier ? ShipmentEventSource::Carrier : ShipmentEventSource::Manual;
        $context['causer_id'] ??= auth()->id();

        return DB::transaction(function () use ($shipment, $status, $context, $fromCarrier): ?OrderShippingEvent {
            $shipment = $this->locked($shipment);

            return $fromCarrier
                ? $this->recordCarrierEvent($shipment, $status, $context)
                : $this->recordManualEvent($shipment, $status, $context);
        });
    }

    /**
     * @param  array<string, mixed>  $context
     */
    private function recordManualEvent(OrderShipping $shipment, ShipmentStatus $status, array $context): ?OrderShippingEvent
    {
        if (! $shipment->canTransitionTo($status)) {
            return null;
        }

        $lastEventDate = $shipment->events()->latest('occurred_at')->value('occurred_at');

        if ($lastEventDate && isset($context['occurred_at']) && $context['occurred_at']->isBefore($lastEventDate)) {
            return null;
        }

        return $this->advance($shipment, $status, $context);
    }

    /**
     * @param  array<string, mixed>  $context
     */
    private function recordCarrierEvent(OrderShipping $shipment, ShipmentStatus $status, array $context): ?OrderShippingEvent
    {
        $externalId = $context['external_id'] ?? null;

        if ($externalId !== null && $shipment->events()->where('external_id', $externalId)->exists()) {
            return null;
        }

        if ($this->outranks($shipment, $status, $context)) {
            return $this->advance($shipment, $status, $context);
        }

        $event = $shipment->logEvent($status, $context);

        event(new OrderShipmentEventRecorded($shipment->order, $shipment, $event, statusChanged: false));

        return $event;
    }

    /**
     * @param  array<string, mixed>  $context
     */
    private function outranks(OrderShipping $shipment, ShipmentStatus $status, array $context): bool
    {
        $current = $shipment->status;

        if ($current === null || $status->rank() > $current->rank()) {
            return true;
        }

        if ($status === $current || $status->rank() < $current->rank()) {
            return false;
        }

        $currentSince = $shipment->events()->where('status', $current)->max('occurred_at');
        $occurredAt = $context['occurred_at'] ?? now();

        return $currentSince === null || ! $occurredAt->isBefore($currentSince);
    }

    /**
     * @param  array<string, mixed>  $context
     */
    private function advance(OrderShipping $shipment, ShipmentStatus $status, array $context): OrderShippingEvent
    {
        $previous = $shipment->status;

        $shipment->update(['status' => $status]);

        $event = $shipment->logEvent($status, $context);

        if ($this->crossesPickup($previous, $status)) {
            $this->onPickedUp($shipment, $event);
        }

        match ($status) {
            ShipmentStatus::Delivered => $this->onDelivered($shipment, $event),
            ShipmentStatus::DeliveryFailed => $this->onDeliveryFailed($shipment),
            ShipmentStatus::Returned => $this->onReturned($shipment, $event),
            default => null,
        };

        event(new OrderShipmentEventRecorded($shipment->order, $shipment, $event, statusChanged: true));

        return $event;
    }

    private function crossesPickup(?ShipmentStatus $previous, ShipmentStatus $status): bool
    {
        $pickupRank = ShipmentStatus::PickedUp->rank();

        return $status !== ShipmentStatus::Returned
            && $status->rank() >= $pickupRank
            && ($previous === null || $previous->rank() < $pickupRank);
    }

    private function locked(OrderShipping $shipment): OrderShipping
    {
        return tap(
            $shipment->newQueryWithoutScopes()->lockForUpdate()->findOrFail($shipment->getKey()),
            function (OrderShipping $locked) use ($shipment): void {
                $locked->setRelation('order', $locked->order()->lockForUpdate()->firstOrFail());

                if ($shipment->relationLoaded('carrier')) {
                    $locked->setRelation('carrier', $shipment->getRelation('carrier'));
                }
            },
        );
    }

    private function onPickedUp(OrderShipping $shipment, OrderShippingEvent $event): void
    {
        $this->stampOnce($shipment, 'shipped_at', $event);

        $shipment->items()
            ->where(fn (Builder $query) => $query
                ->whereNull('fulfillment_status')
                ->orWhere('fulfillment_status', FulfillmentStatus::Pending))
            ->update(['fulfillment_status' => FulfillmentStatus::Shipped]);

        $order = $shipment->order;

        if ($order->status === OrderStatus::New) {
            $order->transitionTo(OrderStatus::Processing);
        }

        (new SyncOrderShippingStatusAction)->execute($order);
    }

    private function onDelivered(OrderShipping $shipment, OrderShippingEvent $event): void
    {
        $this->stampOnce($shipment, 'received_at', $event);

        $shipment->items()
            ->where(fn (Builder $query) => $query
                ->whereNull('fulfillment_status')
                ->orWhereNotIn('fulfillment_status', [FulfillmentStatus::Delivered, FulfillmentStatus::Cancelled]))
            ->update(['fulfillment_status' => FulfillmentStatus::Delivered]);

        $order = $shipment->order;

        (new SyncOrderShippingStatusAction)->execute($order);

        (new CompleteOrderIfFulfilledAction)->execute($order);

        event(new OrderShipmentDelivered($order, $shipment));
    }

    private function onDeliveryFailed(OrderShipping $shipment): void
    {
        event(new OrderShipmentDeliveryFailed($shipment->order, $shipment));
    }

    private function onReturned(OrderShipping $shipment, OrderShippingEvent $event): void
    {
        $this->stampOnce($shipment, 'returned_at', $event);

        $shipment->items()->update([
            'fulfillment_status' => FulfillmentStatus::Cancelled,
        ]);

        $order = $shipment->order;

        (new SyncOrderShippingStatusAction)->execute($order);

        event(new OrderShipmentReturned($order, $shipment));
    }

    private function stampOnce(OrderShipping $shipment, string $column, OrderShippingEvent $event): void
    {
        if ($shipment->getAttribute($column) === null) {
            $shipment->update([$column => $event->occurred_at]);
        }
    }
}
