<?php

declare(strict_types=1);

namespace Shopper\Api\Listeners;

use Illuminate\Contracts\Cache\LockTimeoutException;
use Shopper\Api\Actions\CompleteCartAction;
use Shopper\Cart\Models\Cart;
use Shopper\Cart\Models\Contracts\Cart as CartContract;
use Shopper\Core\Events\Payments\PaymentOrphaned;
use Shopper\Core\Exceptions\PaymentProviderUnavailableException;
use Shopper\Http\Enum\ErrorCode;
use Shopper\Http\Exceptions\ApiValidationException;

final readonly class CompleteOrphanedCart
{
    public function __construct(
        private CompleteCartAction $completeCart,
    ) {}

    public function handle(PaymentOrphaned $event): void
    {
        /** @var Cart|null $cart */
        $cart = resolve(CartContract::class)::query()->forPayment($event->reference, $event->cartId)->first();

        if ($cart === null || $cart->isCompleted() || ($cart->payment_session['reference'] ?? null) !== $event->reference) {
            return;
        }

        try {
            $this->completeCart->execute($cart);
        } catch (LockTimeoutException|PaymentProviderUnavailableException) {
            $event->defer();

            return;
        } catch (ApiValidationException $exception) {
            if ($exception->errorCode !== ErrorCode::PaymentReleased) {
                return;
            }
        }

        $event->resolve();
    }
}
