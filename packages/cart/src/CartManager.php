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
use Shopper\Cart\Pipelines\CartPipelineContext;
use Shopper\Cart\Pipelines\CartPipelineRunner;
use Shopper\Core\Contracts\Priceable;
use Shopper\Core\Enum\AddressType;
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
        $cart->addresses()->updateOrCreate(
            ['type' => $type],
            array_merge($data, ['type' => $type]),
        );
    }

    public function applyCoupon(Cart $cart, string $code): void
    {
        $this->guardCompleted($cart);

        $discount = Discount::query()->where('code', $code)->first();

        if (! $discount instanceof Discount) {
            throw new InvalidDiscountException(__('shopper-cart::messages.discount.not_found'));
        }

        $cart->update(['coupon_code' => $code]);

        CouponApplied::dispatch($cart, $code);
    }

    public function removeCoupon(Cart $cart): void
    {
        $this->guardCompleted($cart);

        $cart->update(['coupon_code' => null]);

        foreach ($cart->lines as $line) {
            $line->adjustments()->delete();
        }

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
            throw new InvalidArgumentException(__('Quantity must be at least 1.'));
        }
    }

    private function guardStock(Model $purchasable, int $quantity): void
    {
        if (! $purchasable instanceof Stockable) {
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
