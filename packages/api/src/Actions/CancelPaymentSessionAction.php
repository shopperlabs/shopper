<?php

declare(strict_types=1);

namespace Shopper\Api\Actions;

use Shopper\Payment\PaymentManager;
use Throwable;

final readonly class CancelPaymentSessionAction
{
    public function __construct(
        private PaymentManager $paymentManager,
    ) {}

    /**
     * Cancel the intent behind a cart payment session at the provider,
     * best-effort: an abandoned session (total changed, currency switched)
     * leaves an open intent in the provider dashboard otherwise. Failures are
     * swallowed: the intent may already be cancelled or confirmed, or the
     * driver may not support cancellation.
     *
     * @param  array<string, mixed>|null  $session
     */
    public function execute(?array $session): void
    {
        if (! $session || ! isset($session['reference'])) {
            return;
        }

        try {
            $this->paymentManager
                ->driver($session['driver'] ?? 'manual')
                ->cancelPayment($session['reference']);
        } catch (Throwable) {
        }
    }
}
