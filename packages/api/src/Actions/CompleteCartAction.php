<?php

declare(strict_types=1);

namespace Shopper\Api\Actions;

use Illuminate\Validation\ValidationException;
use Shopper\Api\Support\ShippingOption;
use Shopper\Cart\Actions\CreateOrderFromCartAction;
use Shopper\Cart\CartManager;
use Shopper\Cart\Exceptions\CartCompletedException;
use Shopper\Cart\Exceptions\InsufficientStockException;
use Shopper\Cart\Exceptions\PriceChangedException;
use Shopper\Cart\Models\Cart;
use Shopper\Cart\Pipelines\CartPipelineContext;
use Shopper\Core\Exceptions\CampaignBudgetExceededException;
use Shopper\Core\Models\Contracts\Order;
use Shopper\Payment\Enum\TransactionStatus;
use Shopper\Payment\Enum\TransactionType;
use Shopper\Payment\Models\PaymentTransaction;

final readonly class CompleteCartAction
{
    public function __construct(
        private GetCartShippingOptionsAction $shippingOptions,
        private CartManager $cartManager,
        private CreateOrderFromCartAction $createOrderFromCart,
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

        $cart->load(['zone.currency', 'zone.carriers', 'lines.purchasable', 'addresses.country', 'customer']);

        if ($cart->lines->isEmpty()) {
            throw ValidationException::withMessages([
                'cart' => __('shopper-api::messages.cart.empty'),
            ]);
        }

        if (! $cart->payment_method_id) {
            throw ValidationException::withMessages([
                'payment_method' => __('shopper-api::messages.payment.method_required'),
            ]);
        }

        $this->ensureEmail($cart);

        $this->refreshShipping($cart);

        try {
            $order = $this->createOrderFromCart->execute(
                $cart,
                fn (CartPipelineContext $context) => $this->guardPaymentSession($cart, $context->total),
                fn (Order $order) => $this->recordInitiatedPayment($cart, $order),
            );
        } catch (CartCompletedException) {
            /** @var Order $order */
            $order = $cart->refresh()->order()->firstOrFail();

            return $order;
        } catch (CampaignBudgetExceededException) {
            throw ValidationException::withMessages([
                'promotion' => __('shopper-cart::messages.discount.campaign_budget_reached'),
            ]);
        } catch (InsufficientStockException|PriceChangedException $exception) {
            throw ValidationException::withMessages([
                'cart' => $exception->getMessage(),
            ]);
        }

        return $order;
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
            throw ValidationException::withMessages([
                'email' => __('shopper-api::messages.cart.email_required'),
            ]);
        }

        $this->cartManager->setEmail($cart, $email);
    }

    /**
     * A shippable cart must carry a delivery choice, and that choice must
     * still be quoted by the carrier. A price drop since selection is
     * absorbed silently; a price increase is never charged without the
     * customer re-consenting to the new total.
     */
    private function refreshShipping(Cart $cart): void
    {
        if (! $this->shippingOptions->requiresShipping($cart)) {
            return;
        }

        if (! $cart->shipping_option_id) {
            throw ValidationException::withMessages([
                'shipping_method' => __('shopper-api::messages.shipping.method_required'),
            ]);
        }

        ['options' => $options] = $this->shippingOptions->execute($cart);

        /** @var ShippingOption|null $option */
        $option = $options->first(
            fn (ShippingOption $option): bool => $option->id() === $cart->shipping_option_id,
        );

        if (! $option) {
            throw ValidationException::withMessages([
                'shipping_method' => __('shopper-api::messages.shipping.option_gone'),
            ]);
        }

        $quoted = $option->rate->amount;
        $frozen = $cart->shipping_amount;

        if ($frozen !== null && $quoted > $frozen) {
            throw ValidationException::withMessages([
                'shipping_method' => __('shopper-api::messages.shipping.price_changed'),
            ]);
        }

        if ($quoted !== $frozen) {
            $this->cartManager->setShippingMethod($cart, $option->id(), $quoted);
        }
    }

    /**
     * An open payment session must match what the order will charge, in
     * amount and in currency: a total or a currency that moved since the
     * intent was created would collect the wrong money. Runs under the cart
     * lock against the exact total the order freezes. The client recreates
     * the session against the new total and retries.
     */
    private function guardPaymentSession(Cart $cart, int $total): void
    {
        $session = $cart->payment_session;

        if (! $session || ! isset($session['amount'])) {
            return;
        }

        if (
            $session['amount'] !== $total
            || (isset($session['currency']) && $session['currency'] !== $cart->currency_code)
        ) {
            throw ValidationException::withMessages([
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
            'amount' => $session['amount'] ?? $order->price_amount,
            'currency_code' => $order->currency_code,
            'reference' => $session['reference'],
        ]);
    }
}
