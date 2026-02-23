<?php

declare(strict_types=1);

namespace Shopper\Core\Traits;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Shopper\Core\Enum\ShipmentStatus;
use Shopper\Core\Models\OrderShippingEvent;

trait HasFulfillmentTransitions
{
    /**
     * @return HasMany<OrderShippingEvent, $this>
     */
    abstract public function events(): HasMany;

    /**
     * @return array<string, list<ShipmentStatus>>
     */
    public static function shipmentTransitions(): array
    {
        return [
            ShipmentStatus::Pending->value => [
                ShipmentStatus::PickedUp,
                ShipmentStatus::Returned,
            ],
            ShipmentStatus::PickedUp->value => [
                ShipmentStatus::InTransit,
                ShipmentStatus::DeliveryFailed,
                ShipmentStatus::Returned,
            ],
            ShipmentStatus::InTransit->value => [
                ShipmentStatus::AtSortingCenter,
                ShipmentStatus::OutForDelivery,
                ShipmentStatus::DeliveryFailed,
                ShipmentStatus::Returned,
            ],
            ShipmentStatus::AtSortingCenter->value => [
                ShipmentStatus::InTransit,
                ShipmentStatus::OutForDelivery,
                ShipmentStatus::DeliveryFailed,
                ShipmentStatus::Returned,
            ],
            ShipmentStatus::OutForDelivery->value => [
                ShipmentStatus::Delivered,
                ShipmentStatus::DeliveryFailed,
                ShipmentStatus::Returned,
            ],
            ShipmentStatus::Delivered->value => [
                ShipmentStatus::Returned,
            ],
            ShipmentStatus::DeliveryFailed->value => [
                ShipmentStatus::InTransit,
                ShipmentStatus::OutForDelivery,
                ShipmentStatus::Returned,
            ],
            ShipmentStatus::Returned->value => [],
        ];
    }

    /**
     * @return list<ShipmentStatus>
     */
    public function allowedTransitions(): array
    {
        if ($this->status === null) {
            return [ShipmentStatus::Pending];
        }

        return static::shipmentTransitions()[$this->status->value] ?? [];
    }

    public function canTransitionTo(ShipmentStatus $status): bool
    {
        return in_array($status, $this->allowedTransitions(), true);
    }

    public function canBeDelivered(): bool
    {
        return $this->canTransitionTo(ShipmentStatus::Delivered);
    }

    /**
     * @param  array{
     *     description?: string,
     *     location?: string,
     *     latitude?: float,
     *     longitude?: float,
     *     occurred_at?: CarbonInterface,
     *     metadata?: array<string, mixed>,
     * }  $context
     */
    public function transitionTo(ShipmentStatus $status, array $context = []): void
    {
        if (! $this->canTransitionTo($status)) {
            return;
        }

        $this->update(['status' => $status]);

        $this->logEvent($status, $context);
    }

    /**
     * @param  array{
     *     description?: string,
     *     location?: string,
     *     latitude?: float,
     *     longitude?: float,
     *     occurred_at?: CarbonInterface,
     *     metadata?: array<string, mixed>,
     * }  $context
     */
    public function logEvent(ShipmentStatus $status, array $context = []): OrderShippingEvent
    {
        return $this->events()->create([
            'status' => $status,
            'description' => $context['description'] ?? null,
            'location' => $context['location'] ?? null,
            'latitude' => $context['latitude'] ?? null,
            'longitude' => $context['longitude'] ?? null,
            'occurred_at' => $context['occurred_at'] ?? now(),
            'metadata' => $context['metadata'] ?? null,
        ]);
    }
}
