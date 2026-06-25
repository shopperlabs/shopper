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
use Throwable;

final readonly class CartManager
{
    public function __construct(
        private CartPipelineRunner $pipelineRunner,
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

        $cart->lines()->findOrFail($lineId)->delete();
    }

    public function clear(Cart $cart): void
    {
        $this->guardCompleted($cart);

        $cart->lines()->delete();
    }

    public function calculate(Cart $cart): CartPipelineContext
    {
        return $this->pipelineRunner->run($cart);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function addAddress(Cart $cart, AddressType $type, array $data): void
    {
        $this->guardCompleted($cart);

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
                'payment_session' => null,
            ]);
        });
    }

    public function applyCoupon(Cart $cart, string $code): void
    {
        $this->guardCompleted($cart);

        $discount = Discount::query()->where('code', $code)->first();

        if (! $discount instanceof Discount) {
            throw new InvalidDiscountException(__('shopper-cart::exceptions.discount_not_found'));
        }

        // A cart can carry several code promotions; the resolver decides which
        // actually apply. The unique (cart_id, discount_id) row keeps re-applying
        // the same code a no-op instead of a doubled discount.
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
