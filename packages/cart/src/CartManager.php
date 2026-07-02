<?php

declare(strict_types=1);

namespace Shopper\Cart;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Shopper\Cart\Events\CouponApplied;
use Shopper\Cart\Events\CouponRemoved;
use Shopper\Cart\Exceptions\CartCompletedException;
use Shopper\Cart\Exceptions\InsufficientStockException;
use Shopper\Cart\Exceptions\InvalidDiscountException;
use Shopper\Cart\Models\Cart;
use Shopper\Cart\Models\CartLine;
use Shopper\Cart\Models\CartLineAdjustment;
use Shopper\Cart\Pipelines\CartPipelineContext;
use Shopper\Cart\Pipelines\CartPipelineRunner;
use Shopper\Core\Contracts\Priceable;
use Shopper\Core\Enum\AddressType;
use Shopper\Core\Enum\PromotionSource;
use Shopper\Core\Models\Contracts\Stockable;
use Shopper\Core\Models\Discount;
use Shopper\Core\Taxes\TaxCalculationContext;
use Shopper\Core\Taxes\TaxCalculator;
use Throwable;

final readonly class CartManager
{
    public function __construct(
        private CartPipelineRunner $pipelineRunner,
        private TaxCalculator $taxCalculator,
    ) {}

    /**
     * @param  array<string, mixed>|null  $metadata
     *
     * @throws Throwable
     */
    public function add(Cart $cart, Priceable&Model $purchasable, int $quantity = 1, ?array $metadata = null): CartLine
    {
        $this->guardQuantity($quantity);
        $this->guardCompleted($cart);
        $this->invalidateTotals($cart);

        return DB::transaction(function () use ($cart, $purchasable, $quantity, $metadata): CartLine {
            $existing = $cart->lines()
                ->where('purchasable_type', $purchasable->getMorphClass())
                ->where('purchasable_id', $purchasable->getKey())
                ->lockForUpdate()
                ->first();

            $requestedQuantity = $existing ? $existing->quantity + $quantity : $quantity;
            $this->guardStock($purchasable, $requestedQuantity);

            if ($existing) {
                $existing->update([
                    'quantity' => $requestedQuantity,
                ]);

                return $existing->refresh();
            }

            $price = $purchasable->getPrice($cart->currency_code);

            return $cart->lines()->create([
                'purchasable_type' => $purchasable->getMorphClass(),
                'purchasable_id' => $purchasable->getKey(),
                'quantity' => $quantity,
                'unit_price_amount' => $price ? $price->amount : 0,
                'metadata' => $metadata,
            ]);
        });
    }

    /**
     * @param  array{quantity?: int, metadata?: array<string, mixed>|null}  $data
     *
     * @throws Throwable
     */
    public function update(Cart $cart, int $lineId, array $data): CartLine
    {
        $this->guardCompleted($cart);
        $this->invalidateTotals($cart);

        return DB::transaction(function () use ($cart, $lineId, $data): CartLine {
            /** @var CartLine $line */
            $line = $cart->lines()->lockForUpdate()->findOrFail($lineId);

            if (isset($data['quantity'])) {
                $this->guardQuantity($data['quantity']);
                $this->guardStock($line->purchasable, $data['quantity']);
            }

            $line->update(Arr::only($data, ['quantity', 'metadata']));

            return $line->refresh();
        });
    }

    public function remove(Cart $cart, int $lineId): void
    {
        $this->guardCompleted($cart);
        $this->invalidateTotals($cart);

        $cart->lines()->findOrFail($lineId)->delete();
    }

    public function clear(Cart $cart): void
    {
        $this->guardCompleted($cart);
        $this->invalidateTotals($cart);

        $cart->lines()->delete();
    }

    public function calculate(Cart $cart): CartPipelineContext
    {
        $context = $this->pipelineRunner->run($cart);

        $cart->updateQuietly(['calculated_at' => now()]);

        return $context;
    }

    /**
     * The read path of the cart totals: rebuilds the pipeline context from the
     * rows the last calculation persisted, without a single write. A cart that
     * was mutated since (invalidated) or whose totals aged past the freshness
     * window is recalculated once, so a time-bound automatic promotion can
     * never stay displayed forever. Checkout never uses this path: the money
     * charged is always recomputed under the cart lock.
     */
    public function totals(Cart $cart): CartPipelineContext
    {
        $ttl = (int) config('shopper.cart.totals_ttl_minutes', 15);

        if ($cart->calculated_at === null || $cart->calculated_at->lt(now()->subMinutes($ttl))) {
            return $this->calculate($cart);
        }

        $cart->loadMissing(['lines.adjustments', 'lines.taxLines', 'addresses.country']);

        $context = new CartPipelineContext($cart);

        foreach ($cart->lines as $line) {
            $lineSubtotal = $line->unit_price_amount * $line->quantity;

            $context->lineSubtotals[$line->id] = $lineSubtotal;
            $context->subtotal += $lineSubtotal;
            $context->discountTotal += (int) $line->adjustments->sum('amount');
            $context->taxTotal += (int) $line->taxLines->sum('amount');
        }

        $context->taxInclusive = $this->resolveTaxInclusive($cart);
        $context->shippingTotal = $cart->shipping_amount ?? 0;

        $goodsTotal = max(0, $context->taxInclusive
            ? $context->subtotal - $context->discountTotal
            : $context->subtotal - $context->discountTotal + $context->taxTotal);

        $context->total = $goodsTotal + $context->shippingTotal;

        return $context;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function addAddress(Cart $cart, AddressType $type, array $data): void
    {
        $this->guardCompleted($cart);
        $this->invalidateTotals($cart);

        $cart->addresses()->updateOrCreate(
            ['type' => $type],
            array_merge($data, ['type' => $type]),
        );
    }

    /**
     * Bind a delivery choice to the cart. The option id is the composite
     * `{carrier_code}:{service_code}` quoted by the shipping options endpoint
     * and the amount is the server-resolved price, never a client value.
     */
    public function setShippingMethod(Cart $cart, string $optionId, int $amount): void
    {
        $this->guardCompleted($cart);
        $this->invalidateTotals($cart);

        $cart->update([
            'shipping_option_id' => $optionId,
            'shipping_amount' => $amount,
        ]);
    }

    public function setPaymentMethod(Cart $cart, int $paymentMethodId): void
    {
        $this->guardCompleted($cart);

        $cart->update(['payment_method_id' => $paymentMethodId]);
    }

    public function setEmail(Cart $cart, string $email): void
    {
        $this->guardCompleted($cart);

        $cart->update(['email' => $email]);
    }

    /**
     * @param  array<string, mixed>|null  $session
     */
    public function setPaymentSession(Cart $cart, ?array $session): void
    {
        $cart->setAttribute('payment_session', $session);
        $cart->save();
    }

    /**
     * @param  array<string, mixed>|null  $metadata
     */
    public function setMetadata(Cart $cart, ?array $metadata): void
    {
        $this->guardCompleted($cart);

        $cart->update(['metadata' => $metadata]);
    }

    /**
     * Re-price the cart in another currency. Each line's unit price is resolved
     * again from its purchasable, and the frozen checkout choices that are
     * bound to the old currency (shipping price, payment session) are dropped
     * so they are quoted again against the new total.
     */
    public function changeCurrency(Cart $cart, string $currencyCode): void
    {
        $this->guardCompleted($cart);
        $this->invalidateTotals($cart);

        DB::transaction(function () use ($cart, $currencyCode): void {
            $cart->loadMissing('lines.purchasable.prices');

            foreach ($cart->lines as $line) {
                $purchasable = $line->purchasable;
                $price = $purchasable instanceof Priceable ? $purchasable->getPrice($currencyCode) : null;

                $line->update(['unit_price_amount' => $price ? $price->amount : 0]);
            }

            $cart->update([
                'currency_code' => $currencyCode,
                'shipping_option_id' => null,
                'shipping_amount' => null,
            ]);

            $this->setPaymentSession($cart, null);
        });
    }

    public function applyCoupon(Cart $cart, string $code): void
    {
        $this->guardCompleted($cart);
        $this->invalidateTotals($cart);

        $discount = Discount::query()->where('code', $code)->first();

        if (! $discount instanceof Discount) {
            throw new InvalidDiscountException(__('shopper-cart::exceptions.discount_not_found'));
        }

        $cart->promotions()->firstOrCreate(
            ['discount_id' => $discount->id],
            ['source' => PromotionSource::Code->value, 'code' => $code],
        );

        CouponApplied::dispatch($cart, $code);
    }

    /**
     * Remove a code promotion from the cart. A null code clears every applied
     * code promotion; a given code removes only that one.
     */
    public function removeCoupon(Cart $cart, ?string $code = null): void
    {
        $this->guardCompleted($cart);
        $this->invalidateTotals($cart);

        $cart->promotions()
            ->where('source', PromotionSource::Code->value)
            ->when($code !== null, fn ($query) => $query->where('code', $code))
            ->delete();

        CartLineAdjustment::query()
            ->whereIn('cart_line_id', $cart->lines()->select('id'))
            ->delete();

        CouponRemoved::dispatch($cart);
    }

    private function guardCompleted(Cart $cart): void
    {
        if ($cart->isCompleted()) {
            throw new CartCompletedException;
        }
    }

    private function invalidateTotals(Cart $cart): void
    {
        $cart->updateQuietly(['calculated_at' => null]);
    }

    private function resolveTaxInclusive(Cart $cart): bool
    {
        $shippingAddress = $cart->shippingAddress();
        $countryCode = $shippingAddress?->country?->cca2;

        if (! $countryCode) {
            return false;
        }

        $zone = $this->taxCalculator->resolveZone(new TaxCalculationContext(
            countryCode: $countryCode,
            provinceCode: $shippingAddress->state,
            customerId: $cart->customer_id,
        ));

        return $zone->is_tax_inclusive ?? false;
    }

    private function guardQuantity(int $quantity): void
    {
        if ($quantity < 1) {
            throw new InvalidArgumentException(__('shopper-cart::exceptions.quantity_minimum'));
        }
    }

    private function guardStock(Model $purchasable, int $quantity): void
    {
        if (! $purchasable instanceof Stockable || ! $purchasable->tracksInventory()) {
            return;
        }

        if ($purchasable->getAttribute('allow_backorder')) {
            return;
        }

        if (! $purchasable->inStock($quantity)) {
            throw new InsufficientStockException(
                purchasable: $purchasable,
                available: $purchasable->stock,
                requested: $quantity,
            );
        }
    }
}
