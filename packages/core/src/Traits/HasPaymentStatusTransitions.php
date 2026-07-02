<?php

declare(strict_types=1);

namespace Shopper\Core\Traits;

use Shopper\Core\Enum\PaymentStatus;
use Shopper\Core\Events\Orders\OrderPaid;
use Shopper\Core\Exceptions\InvalidPaymentStatusTransitionException;

trait HasPaymentStatusTransitions
{
    public function canTransitionPaymentTo(PaymentStatus $status): bool
    {
        return $this->payment_status->canTransitionTo($status);
    }

    public function transitionPaymentTo(PaymentStatus $status): void
    {
        if (! $this->canTransitionPaymentTo($status)) {
            throw InvalidPaymentStatusTransitionException::between($this->payment_status, $status);
        }

        $this->update(['payment_status' => $status]);

        if ($status === PaymentStatus::Paid) {
            event(new OrderPaid($this));
        }
    }
}
