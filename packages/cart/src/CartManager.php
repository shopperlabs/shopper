<?php

declare(strict_types=1);

namespace Shopper\Cart;

use ArrayObject;
use Closure;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Shopper\Cart\Discounts\DiscountValidator;
use Shopper\Cart\Events\CartLinesRepriced;
use Shopper\Cart\Events\CouponApplied;
use Shopper\Cart\Events\CouponRemoved;
use Shopper\Cart\Exceptions\CartCompletedException;
use Shopper\Cart\Exceptions\CartLineLockedException;
use Shopper\Cart\Exceptions\CartLineMetadataConflictException;
use Shopper\Cart\Exceptions\CartOwnedByAnotherCustomerException;
use Shopper\Cart\Exceptions\InsufficientStockException;
use Shopper\Cart\Exceptions\InvalidDiscountException;
use Shopper\Cart\Exceptions\MissingPriceException;
use Shopper\Cart\Exceptions\PaymentSessionCollectedException;
use Shopper\Cart\Exceptions\PriceChangedException;
use Shopper\Cart\Exceptions\QuantityRuleViolationException;
use Shopper\Cart\Models\Cart;
use Shopper\Cart\Models\CartLine;
use Shopper\Cart\Models\CartLineAdjustment;
use Shopper\Cart\Models\CartPromotion;
use Shopper\Cart\Models\Contracts\Cart as CartContract;
use Shopper\Cart\Pipelines\CartPipelineContext;
use Shopper\Cart\Pipelines\CartPipelineRunner;
use Shopper\Core\Contracts\PaymentSessionGateway;
use Shopper\Core\Contracts\PreloadsPrices;
use Shopper\Core\Contracts\Priceable;
use Shopper\Core\Contracts\PriceResolver;
use Shopper\Core\Contracts\QuantityRuleResolver;
use Shopper\Core\Enum\AddressType;
use Shopper\Core\Enum\PromotionSource;
use Shopper\Core\Exceptions\PaymentProviderUnavailableException;
use Shopper\Core\Models\Contracts\Stockable;
use Shopper\Core\Models\Discount;
use Shopper\Core\Pricing\QuantityRule;
use Shopper\Core\Pricing\ResolvedPrice;
use Shopper\Core\Taxes\TaxCalculationContext;
use Shopper\Core\Taxes\TaxCalculator;
use Throwable;

final readonly class CartManager
{
    public function __construct(
        private CartPipelineRunner $pipelineRunner,
        private DiscountValidator $discountValidator,
        private PaymentSessionGateway $paymentSessions,
        private ArrayObject $heldLocks = new ArrayObject,
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

        return $this->changeTotal($cart, function () use ($cart, $purchasable, $quantity, $metadata): CartLine {
            $this->invalidateTotals($cart);

            try {
                return $this->addLine($cart, $purchasable, $quantity, $metadata);
            } catch (UniqueConstraintViolationException) {
                return $this->addLine($cart, $purchasable, $quantity, $metadata);
            }
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

        if (! isset($data['quantity'])) {
            return $this->updateLine($cart, $lineId, $data);
        }

        return $this->changeTotal(
            $cart,
            fn (): CartLine => $this->updateLine($cart, $lineId, $data),
            function () use ($cart, $lineId, $data): bool {
                $quantity = $cart->lines()->whereKey($lineId)->value('quantity');

                return $quantity !== null && (int) $quantity !== $data['quantity'];
            },
        );
    }

    public function remove(Cart $cart, int $lineId): void
    {
        $this->guardCompleted($cart);

        $this->changeTotal($cart, function () use ($cart, $lineId): void {
            $this->invalidateTotals($cart);

            DB::transaction(function () use ($cart, $lineId): void {
                $line = $this->lockLines($cart)->find($lineId) ?? throw (new ModelNotFoundException)->setModel(CartLine::class, [$lineId]);
                $siblings = $this->siblings($cart, $line)->reject(fn (CartLine $sibling): bool => $sibling->is($line));

                $line->delete();
                $cart->setRelation('lines', $cart->lines->reject(fn (CartLine $remaining): bool => $remaining->is($line))->values());

                $this->repriceLines($cart, $siblings, preload: true);
            });
        }, fn (): bool => $cart->lines()->whereKey($lineId)->exists());
    }

    public function clear(Cart $cart): void
    {
        $this->guardCompleted($cart);

        $this->changeTotal($cart, function () use ($cart): void {
            $this->invalidateTotals($cart);

            $cart->lines()->delete();
        });
    }

    public function calculate(Cart $cart): CartPipelineContext
    {
        $context = $this->pipelineRunner->run($cart);

        $cart->updateQuietly(['calculated_at' => now()]);

        return $context;
    }

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

        $context->taxInclusive = $cart->heldTaxInclusive() ?? $this->resolveTaxInclusive($cart);
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

        $attributes = array_merge($data, ['type' => $type]);

        $this->changeTotal(
            $cart,
            function () use ($cart, $type, $attributes): void {
                $this->invalidateTotals($cart);

                $cart->addresses()->updateOrCreate(['type' => $type], $attributes);
            },
            fn (): bool => $type === AddressType::Shipping
                && ($cart->addresses()->where('type', $type)->first()?->fill($attributes)->isDirty() ?? true),
        );
    }

    /**
     * Bind a delivery choice to the cart. The option id is the composite
     * `{carrier_code}:{service_code}` quoted by the shipping options endpoint
     * and the amount is the server-resolved price.
     */
    public function setShippingMethod(Cart $cart, string $optionId, int $amount): void
    {
        $this->guardCompleted($cart);

        $this->changeTotal(
            $cart,
            function () use ($cart, $optionId, $amount): void {
                $this->invalidateTotals($cart);

                $cart->update([
                    'shipping_option_id' => $optionId,
                    'shipping_amount' => $amount,
                ]);
            },
            fn (): bool => $cart->shipping_option_id !== $optionId || (int) $cart->shipping_amount !== $amount,
        );
    }

    public function removeShippingMethod(Cart $cart): void
    {
        $this->guardCompleted($cart);

        $this->changeTotal(
            $cart,
            function () use ($cart): void {
                $this->invalidateTotals($cart);

                $cart->update([
                    'shipping_option_id' => null,
                    'shipping_amount' => null,
                ]);
            },
            fn (): bool => $cart->shipping_option_id !== null,
        );
    }

    public function setPaymentMethod(Cart $cart, int $paymentMethodId): void
    {
        $this->guardCompleted($cart);

        $this->changeTotal(
            $cart,
            fn (): bool => $cart->update(['payment_method_id' => $paymentMethodId]),
            fn (): bool => (int) $cart->payment_method_id !== $paymentMethodId,
            resetsSession: true,
        );
    }

    public function setEmail(Cart $cart, string $email): void
    {
        $this->guardCompleted($cart);

        $cart->update(['email' => $email]);
    }

    /**
     * @template TResult
     *
     * @param  Closure(): TResult  $callback
     * @return TResult
     *
     * @throws LockTimeoutException
     */
    public function withPaymentSessionLock(Cart $cart, Closure $callback): mixed
    {
        $key = $this->paymentSessionLockKey($cart);

        if ($this->heldLocks->offsetExists($key)) {
            return $callback();
        }

        return Cache::lock($key, 180)->block(3, function () use ($key, $callback): mixed {
            $this->heldLocks->offsetSet($key, true);

            try {
                return $callback();
            } finally {
                $this->heldLocks->offsetUnset($key);
            }
        });
    }

    /**
     * @throws PaymentSessionCollectedException
     * @throws PaymentProviderUnavailableException
     */
    public function releasePaymentSession(Cart $cart): void
    {
        $this->syncCheckoutState($cart);
        $this->guardCompleted($cart);
        $this->releaseSession($cart);
    }

    /**
     * @param  array<string, mixed>|null  $session
     */
    public function setPaymentSession(Cart $cart, ?array $session): bool
    {
        $attributes = ['payment_session' => $session, 'payment_reference' => $session['reference'] ?? null];

        $written = $cart->newQuery()
            ->whereKey($cart->getKey())
            ->whereNull('completed_at')
            ->update(Arr::only($cart->newInstance()->forceFill($attributes)->getAttributes(), array_keys($attributes)));

        if ($written === 0) {
            return false;
        }

        $cart->forceFill($attributes)->syncOriginalAttributes(array_keys($attributes));

        return true;
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
     * Re-price the cart in another currency.
     */
    public function changeCurrency(Cart $cart, string $currencyCode): void
    {
        $this->guardCompleted($cart);

        $this->changeTotal($cart, fn () => $this->applyCurrency($cart, $currencyCode), resetsSession: true);
    }

    /**
     * Fold a guest cart into the cart a customer already owns
     *
     * @throws Throwable
     */
    public function merge(Cart $source, Cart $target): Cart
    {
        return $this->changeTotal($source, fn (): Cart => $this->changeTotal(
            $target,
            fn (): Cart => $this->mergeLines($source, $target),
            fn (): bool => $source->lines()->exists(),
            resetsSession: true,
        ));
    }

    /**
     * Hand a guest cart to a customer, folded into the open cart they already own
     *
     * @throws CartCompletedException
     * @throws CartOwnedByAnotherCustomerException
     * @throws LockTimeoutException
     * @throws Throwable
     */
    public function transfer(Cart $cart, int|string $customerId): Cart
    {
        if ($this->ownedBy($cart, $customerId)) {
            return $cart;
        }

        return $this->withPaymentSessionLock($cart, function () use ($cart, $customerId): Cart {
            if ($this->ownedBy($cart->unsetRelations()->refresh(), $customerId)) {
                return $cart;
            }

            /** @var Cart|null $existing */
            $existing = resolve(CartContract::class)::query()
                ->where('customer_id', $customerId)
                ->whereNull('completed_at')
                ->latest('id')
                ->first();

            if ($existing && $cart->payment_session === null) {
                return $this->withPaymentSessionLock($existing, fn (): Cart => $this->mergeOrClaim($cart, $existing->fresh(), $customerId));
            }

            return $this->claim($cart, $customerId);
        });
    }

    /**
     * @return Collection<int, array{line: CartLine, from: int, to: int}>
     *
     * @throws Throwable
     */
    public function reprice(Cart $cart): Collection
    {
        $this->guardCompleted($cart);

        $changes = $this->changeTotal(
            $cart,
            function () use ($cart): Collection {
                $this->invalidateTotals($cart);

                return DB::transaction(fn (): Collection => $this->repriceLines($cart, $this->lockLines($cart), preload: true));
            },
            fn (): bool => $this->movesTotal($this->drifts($cart, $cart->lines()->with('purchasable.prices.currency')->get())[0]),
            resetsSession: true,
        );

        if ($changes->isNotEmpty()) {
            CartLinesRepriced::dispatch($cart, $changes);
        }

        return $changes;
    }

    /**
     * @throws Throwable
     */
    public function changeContext(Cart $cart, ?int $zoneId, ?int $channelId): void
    {
        $this->guardCompleted($cart);

        $changes = fn (): bool => $cart->zone_id !== $zoneId || $cart->channel_id !== $channelId;

        $this->changeTotal(
            $cart,
            function () use ($cart, $zoneId, $channelId, $changes): void {
                if (! $changes()) {
                    return;
                }

                $cart->update([
                    'zone_id' => $zoneId,
                    'channel_id' => $channelId,
                    'shipping_option_id' => null,
                    'shipping_amount' => null,
                    'payment_method_id' => null,
                ]);

                $this->reprice($cart);
            },
            $changes,
            resetsSession: true,
        );
    }

    /**
     * @param  (Closure(): bool)|null  $honoursPayment
     *
     * @throws MissingPriceException
     * @throws PriceChangedException
     * @throws QuantityRuleViolationException
     */
    public function revalidate(Cart $cart, ?Closure $honoursPayment = null): void
    {
        [$drifts, $violation] = $this->drifts($cart, $this->lockLines($cart));

        if (($this->movesTotal($drifts) || $violation !== null) && $honoursPayment !== null && $honoursPayment()) {
            foreach ($drifts as [$line, $price]) {
                if ($price->amount === (int) $line->unit_price_amount) {
                    $this->applyPrice($line, $price);
                }
            }

            return;
        }

        if ($violation !== null) {
            throw $violation;
        }

        foreach ($drifts as [$line, $price]) {
            if ($price->amount > (int) $line->unit_price_amount) {
                throw new PriceChangedException($line->purchasable, (int) $line->unit_price_amount, $price->amount);
            }

            $this->applyPrice($line, $price);
        }
    }

    public function needsRevalidation(Cart $cart): bool
    {
        [$drifts, $violation] = $this->drifts($cart, $this->pricedLines($cart));

        return $this->movesTotal($drifts) || $violation !== null;
    }

    /**
     * @throws MissingPriceException
     * @throws QuantityRuleViolationException
     */
    public function assertSellable(Cart $cart): void
    {
        [, $violation] = $this->drifts($cart, $this->pricedLines($cart));

        if ($violation !== null) {
            throw $violation;
        }
    }

    public function assertQuantityRules(Cart $cart): void
    {
        $cart->loadMissing('lines.purchasable');
        $quantities = $cart->productQuantities();

        foreach ($cart->lines as $line) {
            $this->guardQuantityRule($cart, $line, $line->quantity, $quantities);
        }
    }

    /**
     * @throws Throwable
     */
    public function setLinePrice(Cart $cart, int $lineId, int $amount): CartLine
    {
        if ($amount < 0) {
            throw new InvalidArgumentException(__('shopper-cart::exceptions.price_negative'));
        }

        $this->guardCompleted($cart);

        return $this->changeTotal($cart, function () use ($cart, $lineId, $amount): CartLine {
            $this->invalidateTotals($cart);

            return DB::transaction(function () use ($cart, $lineId, $amount): CartLine {
                $line = $this->lockLines($cart)->find($lineId) ?? throw (new ModelNotFoundException)->setModel(CartLine::class, [$lineId]);

                $line->forceFill([
                    'unit_price_amount' => $amount,
                    'is_custom_price' => true,
                    'pricing' => ['source' => 'custom'],
                ])->save();

                return $line;
            });
        });
    }

    /**
     * @throws Throwable
     */
    public function clearLinePrice(Cart $cart, int $lineId): CartLine
    {
        $this->guardCompleted($cart);

        return $this->changeTotal($cart, function () use ($cart, $lineId): CartLine {
            $this->invalidateTotals($cart);

            return DB::transaction(function () use ($cart, $lineId): CartLine {
                $line = $this->lockLines($cart)->find($lineId) ?? throw (new ModelNotFoundException)->setModel(CartLine::class, [$lineId]);

                $line->forceFill(['is_custom_price' => false, 'pricing' => null]);
                $this->preload($cart, [$line]);

                $this->applyPrice(
                    $line,
                    $this->resolveLinePrice($cart, $line) ?? throw new MissingPriceException($line->purchasable, $cart->currency_code),
                );

                return $line;
            });
        });
    }

    public function applyCoupon(Cart $cart, string $code): void
    {
        $this->guardCompleted($cart);

        $discount = Discount::query()->where('code', $code)->first();

        if (! $discount instanceof Discount) {
            throw new InvalidDiscountException(__('shopper-cart::exceptions.discount_not_found'));
        }

        $this->changeTotal(
            $cart,
            function () use ($cart, $code, $discount): void {
                $this->invalidateTotals($cart);

                $cart->promotions()->firstOrCreate(
                    ['discount_id' => $discount->id],
                    ['source' => PromotionSource::Code->value, 'code' => $code],
                );
            },
            fn (): bool => ! $cart->promotions()->where('discount_id', $discount->id)->exists(),
        );

        CouponApplied::dispatch($cart, $code);
    }

    /**
     * Remove a code promotion from the cart
     */
    public function removeCoupon(Cart $cart, ?string $code = null): void
    {
        $this->guardCompleted($cart);

        $codes = fn () => $cart->promotions()
            ->where('source', PromotionSource::Code->value)
            ->when($code !== null, fn ($query) => $query->where('code', $code));

        $this->changeTotal(
            $cart,
            function () use ($cart, $codes): void {
                if ($codes()->delete() === 0) {
                    return;
                }

                $this->invalidateTotals($cart);

                CartLineAdjustment::query()
                    ->whereIn('cart_line_id', $cart->lines()->select('id'))
                    ->delete();
            },
            fn (): bool => $codes()->exists(),
        );

        CouponRemoved::dispatch($cart);
    }

    /**
     * Drop the code promotions that stopped applying, unless a payment session holds the total
     */
    public function revalidateCoupons(Cart $cart): void
    {
        if ($cart->payment_session !== null) {
            return;
        }

        $promotions = $cart->load('promotions.discount.campaign')->promotions;

        if ($promotions->isEmpty()) {
            return;
        }

        $context = $this->calculate($cart);

        foreach ($promotions as $promotion) {
            $discount = $promotion->discount;

            if ($discount instanceof Discount
                && $discount->is_active
                && $promotion->code !== null
                && $this->discountValidator->validate($discount, $context)->valid) {
                continue;
            }

            if ($promotion->code !== null) {
                $this->removeCoupon($cart, $promotion->code);
            }
        }
    }

    public function holdsLapsedPromotion(Cart $cart): bool
    {
        $applied = $cart->loadMissing(['promotions.discount.campaign', 'promotions.discount.zone'])->promotions->where('computed_amount', '>', 0);

        if ($applied->isEmpty()) {
            return false;
        }

        $context = $this->totals($cart);

        if ($applied->contains(fn (CartPromotion $promotion): bool => ! $promotion->discount instanceof Discount
            || ! $this->discountValidator->validate($promotion->discount, $context)->valid)) {
            return true;
        }

        return $applied
            ->filter(fn (CartPromotion $promotion): bool => $promotion->discount->campaign !== null)
            ->groupBy(fn (CartPromotion $promotion): int => $promotion->discount->campaign_id)
            ->contains(fn (Collection $promotions): bool => ! $promotions->first()->discount->campaign->canAbsorb((int) $promotions->sum('computed_amount')));
    }

    /**
     * @param  array{quantity?: int, metadata?: array<string, mixed>|null}  $data
     *
     * @throws Throwable
     */
    private function updateLine(Cart $cart, int $lineId, array $data): CartLine
    {
        $this->invalidateTotals($cart);

        return DB::transaction(function () use ($cart, $lineId, $data): CartLine {
            $line = $this->lockLines($cart)->find($lineId) ?? throw (new ModelNotFoundException)->setModel(CartLine::class, [$lineId]);

            if (isset($data['quantity'])) {
                if ($line->is_custom_price && $data['quantity'] !== $line->quantity) {
                    throw new CartLineLockedException($line);
                }

                $this->guardQuantity($data['quantity']);
                $this->preload($cart, $this->siblings($cart, $line));
                $this->guardQuantityRule($cart, $line, $data['quantity']);
                $this->guardStock($line->purchasable, $data['quantity']);
            }

            $line->update(Arr::only($data, ['quantity', 'metadata']));

            if ($line->wasChanged('quantity')) {
                $this->repriceLines($cart, $this->siblings($cart, $line));
            }

            return $line->refresh();
        });
    }

    private function applyCurrency(Cart $cart, string $currencyCode): void
    {
        $this->invalidateTotals($cart);

        DB::transaction(function () use ($cart, $currencyCode): void {
            $lines = $this->lockLines($cart);
            $this->preload($cart, $lines, $currencyCode);
            $quantities = $cart->productQuantities();

            foreach ($lines as $line) {
                $this->guardUnlocked($line);

                $price = $this->resolveLinePrice($cart, $line, $currencyCode, $quantities);

                if ($price === null) {
                    throw new MissingPriceException($line->purchasable, $currencyCode);
                }

                $this->applyPrice($line, $price);
            }

            $cart->update([
                'currency_code' => $currencyCode,
                'shipping_option_id' => null,
                'shipping_amount' => null,
            ]);
        });
    }

    /**
     * @throws Throwable
     */
    private function mergeLines(Cart $source, Cart $target): Cart
    {
        [$merged, $changes] = DB::transaction(function () use ($source, $target): array {
            /** @var Cart|null $source */
            $source = $source->newQuery()->lockForUpdate()->find($source->getKey());

            if ($source === null) {
                return [$target, new Collection];
            }

            /** @var Cart $target */
            $target = $target->newQuery()->lockForUpdate()->findOrFail($target->getKey());

            $this->guardCompleted($source);
            $this->guardCompleted($target);
            $this->invalidateTotals($target);

            $source->loadMissing(['lines', 'promotions']);

            $existingLines = $target->lines()
                ->lockForUpdate()
                ->get()
                ->keyBy(fn (CartLine $line): string => $line->purchasable_type.':'.$line->purchasable_id);

            $currencyChanged = $source->currency_code !== $target->currency_code;
            $moved = [];

            foreach ($source->lines as $line) {
                $existing = $existingLines->get($line->purchasable_type.':'.$line->purchasable_id);

                if ($existing) {
                    $this->guardUnlocked($existing);
                    $this->guardUnlocked($line);
                    $this->guardSameMetadata($existing, $line->metadata);

                    $existing->update(['quantity' => $existing->quantity + $line->quantity]);
                    $line->delete();

                    continue;
                }

                if ($currencyChanged) {
                    $this->guardUnlocked($line);
                    $moved[] = $line->id;
                }

                $line->update(['cart_id' => $target->id]);
            }

            $lines = $this->lockLines($target);
            $this->preload($target, $lines);
            $quantities = $target->productQuantities();

            foreach ($lines->whereIn('id', $moved) as $line) {
                if ($this->resolveLinePrice($target, $line, productQuantities: $quantities) === null) {
                    throw new MissingPriceException($line->purchasable, $target->currency_code);
                }
            }

            $changes = $this->repriceLines($target, $lines)
                ->reject(fn (array $change): bool => in_array($change['line']->id, $moved, true))
                ->values();

            foreach ($source->promotions as $promotion) {
                $target->promotions()->firstOrCreate(
                    ['discount_id' => $promotion->discount_id],
                    ['source' => $promotion->source, 'code' => $promotion->code],
                );
            }

            if ($source->lines->isNotEmpty()) {
                $target->update(['shipping_option_id' => null, 'shipping_amount' => null]);
            }

            $source->delete();

            return [$target, $changes];
        });

        if ($changes->isNotEmpty()) {
            CartLinesRepriced::dispatch($merged, $changes);
        }

        return $merged;
    }

    /**
     * @param  array<string, mixed>|null  $metadata
     */
    private function addLine(Cart $cart, Priceable&Model $purchasable, int $quantity, ?array $metadata): CartLine
    {
        return DB::transaction(function () use ($cart, $purchasable, $quantity, $metadata): CartLine {
            $existing = $this->lockLines($cart)->first(
                fn (CartLine $line): bool => $line->purchasable_type === $purchasable->getMorphClass()
                    && (int) $line->purchasable_id === (int) $purchasable->getKey()
            );

            if ($existing) {
                $siblings = $this->siblings($cart, $existing);
                $this->preload($cart, $siblings);

                $this->guardUnlocked($existing);
                $this->guardSameMetadata($existing, $metadata);
                $this->guardQuantityRule($cart, $existing, $existing->quantity + $quantity);
                $this->guardStock($purchasable, $existing->quantity + $quantity);

                $existing->update(['quantity' => $existing->quantity + $quantity]);
                $this->repriceLines($cart, $siblings);

                return $existing->refresh();
            }

            $line = $cart->lines()->make([
                'purchasable_type' => $purchasable->getMorphClass(),
                'purchasable_id' => $purchasable->getKey(),
                'quantity' => $quantity,
                'metadata' => $metadata,
            ])->setRelation('purchasable', $purchasable);

            $siblings = $this->siblings($cart, $line);
            $this->preload($cart, $siblings->concat([$line]));

            $this->guardQuantityRule($cart, $line, $quantity);
            $this->guardStock($purchasable, $quantity);

            $cart->setRelation('lines', $cart->lines->push($line));

            $this->applyPrice(
                $line,
                $this->resolveLinePrice($cart, $line) ?? throw new MissingPriceException($purchasable, $cart->currency_code),
            );

            $this->repriceLines($cart, $siblings);

            return $line;
        }, 3);
    }

    /**
     * @param  array<string, int>|null  $productQuantities
     */
    private function resolveLinePrice(Cart $cart, CartLine $line, ?string $currencyCode = null, ?array $productQuantities = null): ?ResolvedPrice
    {
        $purchasable = $line->purchasable;

        if ($line->is_custom_price || ! $purchasable instanceof Priceable) {
            return null;
        }

        return $purchasable->resolvePrice($cart->pricingContextFor($line, $currencyCode, productQuantities: $productQuantities));
    }

    /**
     * @return EloquentCollection<int, CartLine>
     */
    private function lockLines(Cart $cart): EloquentCollection
    {
        if ($cart->newQuery()->whereKey($cart->getKey())->lockForUpdate()->firstOrFail(['completed_at'])->completed_at !== null) {
            throw new CartCompletedException;
        }

        /** @var EloquentCollection<int, CartLine> $lines */
        $lines = $cart->lines()->lockForUpdate()->with('purchasable.prices.currency')->get();

        $cart->setRelation('lines', $lines);

        return $lines;
    }

    /**
     * @param  EloquentCollection<int, CartLine>  $lines
     * @return array{0: Collection<int, array{0: CartLine, 1: ResolvedPrice}>, 1: MissingPriceException|QuantityRuleViolationException|null}
     */
    private function drifts(Cart $cart, EloquentCollection $lines): array
    {
        $cart->setRelation('lines', $lines);
        $this->preload($cart, $lines);
        $quantities = $cart->productQuantities();

        $prices = $lines
            ->toBase()
            ->map(fn (CartLine $line): array => [$line, $this->resolveLinePrice($cart, $line, productQuantities: $quantities)]);

        $drifts = $prices
            ->filter(fn (array $drift): bool => $drift[1] !== null && (
                $drift[1]->amount !== (int) $drift[0]->unit_price_amount
                || $this->pricingDiffers($drift[0], $drift[1])
            ))
            ->values();

        $unpriced = $prices->first(fn (array $drift): bool => $drift[1] === null && ! $drift[0]->is_custom_price && $drift[0]->purchasable instanceof Priceable);

        if ($unpriced !== null) {
            return [$drifts, new MissingPriceException($unpriced[0]->purchasable, $cart->currency_code)];
        }

        try {
            $this->assertQuantityRules($cart);
        } catch (QuantityRuleViolationException $exception) {
            return [$drifts, $exception];
        }

        return [$drifts, null];
    }

    /**
     * @return EloquentCollection<int, CartLine>
     */
    private function pricedLines(Cart $cart): EloquentCollection
    {
        $loaded = $cart->relationLoaded('lines') && $cart->lines->every(
            fn (CartLine $line): bool => $line->relationLoaded('purchasable') && ($line->purchasable === null || $line->purchasable->relationLoaded('prices'))
        );

        return $loaded ? $cart->lines : $cart->lines()->with('purchasable.prices.currency')->get();
    }

    /**
     * @param  Collection<int, array{0: CartLine, 1: ResolvedPrice}>  $drifts
     */
    private function movesTotal(Collection $drifts): bool
    {
        return $drifts->contains(fn (array $drift): bool => $drift[1]->amount !== (int) $drift[0]->unit_price_amount);
    }

    /**
     * @param  iterable<int, CartLine>  $lines
     */
    private function preload(Cart $cart, iterable $lines, ?string $currencyCode = null): void
    {
        $resolver = resolve(PriceResolver::class);

        $purchasables = (new Collection($lines))
            ->reject(fn (CartLine $line): bool => (bool) $line->is_custom_price)
            ->map(fn (CartLine $line): ?Model => $line->purchasable)
            ->filter(fn (?Model $purchasable): bool => $purchasable instanceof Priceable)
            ->values();

        if ($resolver instanceof PreloadsPrices && $purchasables->isNotEmpty()) {
            $resolver->preload($purchasables, $cart->pricingContext(currencyCode: $currencyCode));
        }
    }

    /**
     * @return Collection<int, CartLine>
     */
    private function siblings(Cart $cart, CartLine $line): Collection
    {
        $key = $line->productKey();

        return $cart->lines->filter(fn (CartLine $sibling): bool => $sibling->productKey() === $key)->values()->toBase();
    }

    /**
     * @param  iterable<int, CartLine>  $lines
     * @return Collection<int, array{line: CartLine, from: int, to: int}>
     */
    private function repriceLines(Cart $cart, iterable $lines, bool $preload = false): Collection
    {
        if ($preload) {
            $this->preload($cart, $lines);
        }

        $quantities = $cart->productQuantities();

        return (new Collection($lines))
            ->map(fn (CartLine $line): ?array => $this->applyPrice($line, $this->resolveLinePrice($cart, $line, productQuantities: $quantities)))
            ->filter()
            ->values();
    }

    /**
     * @return array{line: CartLine, from: int, to: int}|null
     */
    private function applyPrice(CartLine $line, ?ResolvedPrice $price): ?array
    {
        if ($price === null) {
            return null;
        }

        $from = (int) $line->unit_price_amount;

        $line->forceFill(['unit_price_amount' => $price->amount]);

        if ($this->pricingDiffers($line, $price)) {
            $line->forceFill(['pricing' => $price->meta ?: null]);
        }

        $line->save();

        return $from === $price->amount ? null : ['line' => $line, 'from' => $from, 'to' => $price->amount];
    }

    private function pricingDiffers(CartLine $line, ResolvedPrice $price): bool
    {
        return $this->canonicalPricing($price->meta) !== $this->canonicalPricing($line->pricing ?? []);
    }

    /**
     * @param  array<array-key, mixed>  $pricing
     * @return array<array-key, mixed>
     */
    private function canonicalPricing(array $pricing): array
    {
        if (! array_is_list($pricing)) {
            ksort($pricing);
        }

        return array_map(fn (mixed $value): mixed => is_array($value) ? $this->canonicalPricing($value) : $value, $pricing);
    }

    /**
     * @param  array<string, int>|null  $productQuantities
     */
    private function guardQuantityRule(Cart $cart, CartLine $line, int $quantity, ?array $productQuantities = null): void
    {
        $purchasable = $line->purchasable;

        if ($line->is_custom_price || ! $purchasable instanceof Priceable) {
            return;
        }

        $rule = resolve(QuantityRuleResolver::class)->resolve($purchasable, $cart->pricingContextFor($line, quantity: $quantity, productQuantities: $productQuantities));

        if ($rule !== null && ! $rule->allows($quantity)) {
            throw new QuantityRuleViolationException($purchasable, $quantity, $rule);
        }

        if ($quantity > CartLine::MAXIMUM_QUANTITY) {
            throw new QuantityRuleViolationException($purchasable, $quantity, new QuantityRule(maximum: CartLine::MAXIMUM_QUANTITY));
        }
    }

    private function guardUnlocked(CartLine $line): void
    {
        if ($line->is_custom_price) {
            throw new CartLineLockedException($line);
        }
    }

    /**
     * @param  array<string, mixed>|null  $metadata
     */
    private function guardSameMetadata(CartLine $line, ?array $metadata): void
    {
        if ($metadata !== null && $metadata !== [] && $this->sortedMetadata($metadata) !== $this->sortedMetadata($line->metadata ?? [])) {
            throw new CartLineMetadataConflictException($line);
        }
    }

    /**
     * @param  array<array-key, mixed>  $metadata
     * @return array<array-key, mixed>
     */
    private function sortedMetadata(array $metadata): array
    {
        if (! array_is_list($metadata)) {
            ksort($metadata, SORT_STRING);
        }

        return array_map(fn (mixed $value): mixed => is_array($value) ? $this->sortedMetadata($value) : $value, $metadata);
    }

    private function ownedBy(Cart $cart, int|string $customerId): bool
    {
        $this->guardCompleted($cart);

        if ($cart->customer_id !== null && ! $cart->belongsToCustomer($customerId)) {
            throw new CartOwnedByAnotherCustomerException;
        }

        return $cart->customer_id !== null;
    }

    private function mergeOrClaim(Cart $cart, ?Cart $existing, int|string $customerId): Cart
    {
        if ($existing === null || $existing->payment_session !== null || $existing->isCompleted()) {
            return $this->claim($cart, $customerId);
        }

        try {
            $merged = $this->merge($cart, $existing);
        } catch (CartLineLockedException|CartLineMetadataConflictException|MissingPriceException|ModelNotFoundException) {
            return $this->claim($cart, $customerId);
        }

        $this->revalidateCoupons($merged);

        return $merged;
    }

    private function claim(Cart $cart, int|string $customerId): Cart
    {
        $this->changeTotal($cart, fn (): bool => $cart->update(['customer_id' => $customerId]));

        if ($cart->payment_session === null) {
            $this->reprice($cart);
        }

        $this->revalidateCoupons($cart);

        return $cart;
    }

    /**
     * @template TResult
     *
     * @param  Closure(): TResult  $mutation
     * @param  (Closure(): bool)|null  $changesTotal
     * @return TResult
     */
    private function changeTotal(Cart $cart, Closure $mutation, ?Closure $changesTotal = null, bool $resetsSession = false): mixed
    {
        $reentrant = $this->heldLocks->offsetExists($this->paymentSessionLockKey($cart));

        return $this->withPaymentSessionLock($cart, function () use ($cart, $mutation, $changesTotal, $resetsSession, $reentrant): mixed {
            if (! $reentrant) {
                $this->syncCheckoutState($cart);
            }

            $this->guardCompleted($cart);

            if ($this->mustRelease($cart, $resetsSession) && ($changesTotal === null || $changesTotal())) {
                $this->releaseSession($cart);
            }

            return $mutation();
        });
    }

    private function releaseSession(Cart $cart): void
    {
        $session = $cart->payment_session;

        if ($session === null) {
            return;
        }

        if (! $this->paymentSessions->release($session)) {
            throw new PaymentSessionCollectedException;
        }

        $this->setPaymentSession($cart, null);
    }

    private function mustRelease(Cart $cart, bool $resetsSession): bool
    {
        return $resetsSession ? $cart->payment_session !== null : $cart->holdsProviderPaymentSession();
    }

    private function syncCheckoutState(Cart $cart): void
    {
        $columns = ['payment_session', 'payment_reference', 'completed_at', 'payment_method_id', 'shipping_option_id', 'shipping_amount', 'zone_id', 'channel_id', 'currency_code', 'customer_id'];

        $current = $cart->newQuery()->whereKey($cart->getKey())->first([$cart->getKeyName(), ...$columns]);

        if ($current === null) {
            return;
        }

        $cart->forceFill($current->only($columns))->syncOriginalAttributes($columns);
    }

    private function paymentSessionLockKey(Cart $cart): string
    {
        return "cart:payment-session:{$cart->public_id}";
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

        $zone = resolve(TaxCalculator::class)->resolveZone(new TaxCalculationContext(
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
