<?php

declare(strict_types=1);

namespace Shopper\Api\Actions;

use Shopper\Cart\Actions\CreateOrderFromCartAction;
use Shopper\Cart\CartManager;
use Shopper\Cart\Exceptions\CartCompletedException;
use Shopper\Cart\Exceptions\DiscountLimitReachedException;
use Shopper\Cart\Exceptions\InsufficientStockException;
use Shopper\Cart\Exceptions\MissingPriceException;
use Shopper\Cart\Exceptions\PriceChangedException;
use Shopper\Cart\Exceptions\PromotionUnavailableException;
use Shopper\Cart\Exceptions\QuantityRuleViolationException;
use Shopper\Cart\Models\Cart;
use Shopper\Cart\Pipelines\CartPipelineContext;
use Shopper\Core\Contracts\PaymentSessionGateway;
use Shopper\Core\Exceptions\CampaignBudgetExceededException;
use Shopper\Core\Models\Contracts\Order;
use Shopper\Http\Enum\ErrorCode;
use Shopper\Http\Exceptions\ApiValidationException;
use Shopper\Payment\Actions\SettlePayment;
use Shopper\Payment\Enum\TransactionStatus;
use Shopper\Payment\Enum\TransactionType;
use Shopper\Payment\Jobs\SyncPendingPaymentJob;
use Shopper\Payment\Models\PaymentTransaction;
use Throwable;

final readonly class CompleteCartAction
{
    public function __construct(
        private RefreshCartShippingAction $refreshShipping,
        private CartManager $cartManager,
        private CreateOrderFromCartAction $createOrderFromCart,
        private SettlePayment $settle,
        private PaymentSessionGateway $paymentSessions,
    ) {}

    /**
     * Turn the cart into an order. Placement is idempotent: completing a cart
     * that already produced an order answers with that order instead of
     * failing or duplicating it. The shipping price is re-quoted from the
     * stored option id right before the order freezes it, so a stale snapshot
     * can never be charged.
     */
    public function execute(Cart $cart): Order
    {
        if ($cart->isCompleted()) {
            /** @var Order $order */
            $order = $cart->order()->firstOrFail();

            return $order;
        }

        return $this->cartManager->withPaymentSessionLock($cart, fn (): Order => $this->complete($cart->unsetRelations()->refresh()));
    }

    private function complete(Cart $cart): Order
    {
        if ($cart->isCompleted()) {
            /** @var Order $order */
            $order = $cart->order()->firstOrFail();

            return $order;
        }

        $cart->load(['zone.currency', 'zone.carriers', $cart->payment_session === null ? 'lines.purchasable' : 'lines.purchasable.prices.currency', 'addresses.country', 'customer']);

        if ($cart->lines->isEmpty()) {
            throw ApiValidationException::withCode(ErrorCode::CartEmpty, [
                'cart' => __('shopper-api::messages.cart.empty'),
            ]);
        }

        if (! $cart->payment_method_id) {
            throw ApiValidationException::withCode(ErrorCode::PaymentMethodRequired, [
                'payment_method' => __('shopper-api::messages.payment.method_required'),
            ]);
        }

        $this->ensureEmail($cart);

        $session = $cart->payment_session;
        $checked = false;
        $collected = null;
        $isCollected = function () use ($session, &$checked, &$collected): ?bool {
            if (! $checked) {
                $collected = $session === null ? false : $this->paymentSessions->collected($session);
                $checked = true;
            }

            return $collected;
        };

        $this->refreshShipping->execute($cart, $isCollected);

        if ($session !== null && ($this->cartManager->needsRevalidation($cart) || ($cart->holdsProviderPaymentSession() && $cart->promotions()->where('computed_amount', '>', 0)->exists()))) {
            $isCollected();
        }

        try {
            $order = $this->createOrderFromCart->execute(
                $cart,
                fn (CartPipelineContext $context) => $this->guardPaymentSession($cart, $context->total),
                fn (Order $order) => $this->recordInitiatedPayment($cart, $order),
                fn (): bool => $cart->payment_session === $session && $isCollected() !== false,
            );
        } catch (CartCompletedException) {
            /** @var Order $order */
            $order = $cart->refresh()->order()->firstOrFail();

            return $order;
        } catch (CampaignBudgetExceededException) {
            $this->reopenPromotions($cart);

            throw ApiValidationException::withCode(ErrorCode::PromotionBudgetReached, [
                'promotion' => __('shopper-cart::messages.discount.campaign_budget_reached'),
            ]);
        } catch (PromotionUnavailableException $exception) {
            $this->reopenPromotions($cart);

            throw ApiValidationException::withCode(ErrorCode::PromotionNotApplicable, [
                'promotion' => $exception->getMessage(),
            ]);
        } catch (DiscountLimitReachedException $exception) {
            $this->reopenPromotions($cart);

            throw ApiValidationException::withCode(ErrorCode::PromotionLimitReached, [
                'promotion' => $exception->getMessage(),
            ]);
        } catch (InsufficientStockException $exception) {
            if ($session !== null && $isCollected() !== false && $this->paymentSessions->refund($session)) {
                $this->cartManager->setPaymentSession($cart, null);

                throw ApiValidationException::withCode(ErrorCode::PaymentReleased, [
                    'cart' => __('shopper-api::messages.payment.released'),
                ]);
            }

            throw ApiValidationException::withCode(ErrorCode::StockInsufficient, [
                'cart' => $exception->getMessage(),
            ]);
        } catch (MissingPriceException $exception) {
            throw ApiValidationException::withCode(ErrorCode::PriceMissing, ['cart' => $exception->getMessage()]);
        } catch (QuantityRuleViolationException $exception) {
            throw ApiValidationException::withCode(ErrorCode::QuantityRuleViolated, ['cart' => $exception->getMessage()], $exception->context());
        } catch (PriceChangedException $exception) {
            try {
                $this->cartManager->reprice($cart);
            } catch (CartCompletedException) {
                /** @var Order $order */
                $order = $cart->refresh()->order()->firstOrFail();

                return $order;
            }

            throw ApiValidationException::withCode(ErrorCode::PriceChanged, [
                'cart' => $exception->getMessage(),
            ]);
        }

        $this->settlePayment($cart, $order);

        return $order;
    }

    /**
     * The browser confirms the payment before the storefront completes the
     * cart, so the provider often speaks before the order exists. Once the
     * order and its initiate transaction are committed, the events that
     * landed early are replayed, and a provider check is queued for a
     * payment still pending: the response never waits on the provider, and
     * placement is never blocked by the settlement. The order is the durable
     * anchor, the scheduled reconciliation is the safety net.
     */
    private function settlePayment(Cart $cart, Order $order): void
    {
        $reference = $cart->payment_session['reference'] ?? null;

        if (! is_string($reference)) {
            return;
        }

        try {
            $this->settle->execute($reference);

            if (config('shopper.payment.reconciliation.pull_on_completion', true)) {
                SyncPendingPaymentJob::dispatch($order->getKey(), $reference)
                    ->onQueue(config('shopper.payment.reconciliation.queue'));
            }
        } catch (Throwable $exception) {
            report($exception);
        }
    }

    private function reopenPromotions(Cart $cart): void
    {
        $this->cartManager->releasePaymentSession($cart);
        $this->cartManager->calculate($cart->unsetRelations());
    }

    /**
     * Freeze the contact email on the cart so the order can be confirmed and
     * looked up. A customer cart falls back to the customer's own email; a
     * guest cart must carry one, set at creation or with the checkout address.
     */
    private function ensureEmail(Cart $cart): void
    {
        if ($cart->email !== null) {
            return;
        }

        $email = $cart->customer?->getAttribute('email');

        if (! is_string($email)) {
            throw ApiValidationException::withCode(ErrorCode::EmailRequired, [
                'email' => __('shopper-api::messages.cart.email_required'),
            ]);
        }

        $this->cartManager->setEmail($cart, $email);
    }

    private function guardPaymentSession(Cart $cart, int $total): void
    {
        $session = $cart->payment_session;

        if (! $session || ! isset($session['amount'])) {
            if (($cart->paymentMethod->driver ?? 'manual') !== 'manual') {
                throw ApiValidationException::withCode(ErrorCode::PaymentSessionRequired, [
                    'payment_session' => __('shopper-api::messages.payment.session_required'),
                ]);
            }

            return;
        }

        if (($session['driver'] ?? 'manual') === 'manual' && ($cart->paymentMethod->driver ?? 'manual') === 'manual') {
            return;
        }

        if (
            $session['amount'] !== $total
            || (isset($session['currency']) && $session['currency'] !== $cart->currency_code)
        ) {
            throw ApiValidationException::withCode(ErrorCode::PaymentSessionMismatch, [
                'payment_session' => __('shopper-api::messages.payment.session_mismatch'),
            ]);
        }
    }

    /**
     * The intent opened against the cart is journalized on the order it paid
     * for, so the webhook that confirms or fails the payment later finds the
     * order through the same reference.
     */
    private function recordInitiatedPayment(Cart $cart, Order $order): void
    {
        $session = $cart->payment_session;

        if (! $session || ! isset($session['reference'])) {
            return;
        }

        PaymentTransaction::query()->create([
            'order_id' => $order->id,
            'payment_method_id' => $cart->payment_method_id,
            'driver' => $session['driver'] ?? 'manual',
            'type' => TransactionType::Initiate,
            'status' => TransactionStatus::Pending,
            'amount' => $order->price_amount,
            'currency_code' => $order->currency_code,
            'reference' => $session['reference'],
        ]);
    }
}
