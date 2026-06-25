<?php

declare(strict_types=1);

namespace Shopper\Core\Traits;

use Shopper\Core\Enum\OrderStatus;
use Shopper\Core\Exceptions\InvalidOrderStatusTransitionException;

trait HasOrderStatusTransitions
{
    public function canTransitionTo(OrderStatus $status): bool
    {
        return $this->status->canTransitionTo($status);
    }

    public function transitionTo(OrderStatus $status): void
    {
        if (! $this->canTransitionTo($status)) {
            throw InvalidOrderStatusTransitionException::between($this->status, $status);
        }

        $this->update([
            'status' => $status,
            ...$this->statusTimestamps($status),
        ]);
    }

    /**
     * @return array<string, \Illuminate\Support\Carbon>
     */
    private function statusTimestamps(OrderStatus $status): array
    {
        return match ($status) {
            OrderStatus::Cancelled => ['cancelled_at' => now()],
            OrderStatus::Archived => ['archived_at' => now()],
            default => [],
        };
    }
}
