<?php

declare(strict_types=1);

namespace Shopper\Cart\Listeners;

use Shopper\Cart\Models\Contracts\Cart as CartContract;
use Shopper\Core\Events\Payments\PaymentOrphaned;

final class RecognizeOrphanedPayment
{
    public function handle(PaymentOrphaned $event): void
    {
        if (resolve(CartContract::class)::query()->forPayment($event->reference, $event->cartId)->exists()) {
            $event->recognize();
        }
    }
}
