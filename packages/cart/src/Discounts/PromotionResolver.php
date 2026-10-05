<?php

declare(strict_types=1);

namespace Shopper\Cart\Discounts;

use Illuminate\Support\Collection;
use Shopper\Cart\Models\CartLine;
use Shopper\Cart\Models\CartLineAdjustment;
use Shopper\Cart\Models\CartPromotion;
use Shopper\Cart\Pipelines\CartPipelineContext;
use Shopper\Core\Enum\DiscountApplyTo;
use Shopper\Core\Enum\DiscountCondition;
use Shopper\Core\Enum\DiscountType;
use Shopper\Core\Enum\ExclusivityClass;
use Shopper\Core\Models\Contracts\Product;
use Shopper\Core\Models\Contracts\ProductVariant;
use Shopper\Core\Models\Discount;

final readonly class PromotionResolver
{
    private const array CLASS_ORDER = [
        ExclusivityClass::Order->value => 0,
        ExclusivityClass::Product->value => 1,
        ExclusivityClass::Shipping->value => 2,
    ];

    public function __construct(
        private DiscountValidator $validator,
    ) {}

    /**
     * Apply every promotion on the cart deterministically.
     *
     * Valid candidates are ordered by (exclusivity class, priority desc, id).
     * The first promotion applied in a class always applies; a later one in the
     * same class stacks only when it and that first one are both combinable,
     * different classes always stack, and the count is capped at the configured
     * maximum. Each applied promotion draws from a shared per-line balance so a
     * line can never be discounted below zero and the same cart state always
     * yields the same adjustments. A promotion that discounts nothing, or that
     * its campaign's remaining spend budget cannot absorb, is dropped before it
     * takes its class or a slot under the cap. Suppressed or invalid promotions
     * stay on the cart with a zero computed amount.
     */
    public function resolve(CartPipelineContext $context): void
    {
        $cart = $context->cart;

        if ($cart->holdsProviderPaymentSession()) {
            $context->discountTotal = (int) $cart->lines->sum(fn (CartLine $line): int => (int) $line->adjustments->sum('amount'));

            return;
        }

        CartLineAdjustment::query()
            ->whereIn('cart_line_id', $cart->lines->pluck('id'))
            ->delete();

        // The runner eager-loads `lines.adjustments`; drop the cached relation so
        // later pipes (tax) compute on the rewritten adjustments, not the old set.
        $cart->lines->each(fn (CartLine $line) => $line->unsetRelation('adjustments'));

        $remaining = [];

        foreach ($cart->lines as $line) {
            $remaining[$line->id] = $context->lineSubtotals[$line->id] ?? 0;
        }

        $total = 0;
        $sequence = 0;
        $adjustments = [];
        $computed = [];
        $campaignSpend = [];
        $classCombinable = [];
        $max = (int) config('shopper.cart.max_promotions', 5);

        foreach ($this->order($this->validCandidates($context)) as $promotion) {
            if ($sequence >= $max) {
                break;
            }

            /** @var Discount $discount */
            $discount = $promotion->discount;
            $class = $discount->exclusivity_class->value;

            if (array_key_exists($class, $classCombinable) && ! ($classCombinable[$class] && $discount->combinable)) {
                continue;
            }

            $lineAmounts = [];

            foreach ($this->rawAmounts($discount, $context) as $lineId => $raw) {
                $applicable = min($raw, $remaining[$lineId] ?? 0);

                if ($applicable > 0) {
                    $lineAmounts[$lineId] = $applicable;
                }
            }

            $promotionAmount = array_sum($lineAmounts);

            if ($promotionAmount === 0) {
                continue;
            }

            $campaign = $discount->campaign;

            if ($campaign !== null) {
                if (! $campaign->canAbsorb(($campaignSpend[$campaign->id] ?? 0) + $promotionAmount)) {
                    continue;
                }

                $campaignSpend[$campaign->id] = ($campaignSpend[$campaign->id] ?? 0) + $promotionAmount;
            }

            $classCombinable[$class] ??= $discount->combinable;

            foreach ($lineAmounts as $lineId => $applicable) {
                $remaining[$lineId] -= $applicable;

                $adjustments[] = [
                    'cart_line_id' => $lineId,
                    'cart_promotion_id' => $promotion->id,
                    'amount' => $applicable,
                    'code' => $discount->code,
                    'discount_id' => $discount->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            $computed[$promotion->id] = ['amount' => $promotionAmount, 'sequence' => $sequence];
            $total += $promotionAmount;
            $sequence++;
        }

        $this->persistComputedAmounts($cart->promotions, $computed);

        if ($adjustments !== []) {
            CartLineAdjustment::query()->insert($adjustments);
        }

        $context->discountTotal = $total;
    }

    /**
     * Whether a discount would reduce the cart on its own, ignoring suppression
     * by other promotions. Lets the apply endpoint reject a code that cannot
     * benefit the current cart while still accepting one merely suppressed by a
     * higher-priority exclusive.
     */
    public function wouldApply(Discount $discount, CartPipelineContext $context): bool
    {
        $amount = array_sum($this->rawAmounts($discount, $context));

        return $amount > 0 && ($discount->campaign?->canAbsorb($amount) ?? true);
    }

    /**
     * @return Collection<int, CartPromotion>
     */
    private function validCandidates(CartPipelineContext $context): Collection
    {
        return $context->cart->promotions->filter(function (CartPromotion $promotion) use ($context): bool {
            $discount = $promotion->discount;

            if (! $discount instanceof Discount || ! $discount->is_active) {
                return false;
            }

            return $this->validator->validate($discount, $context)->valid;
        })->values();
    }

    /**
     * @param  Collection<int, CartPromotion>  $candidates
     * @return list<CartPromotion>
     */
    private function order(Collection $candidates): array
    {
        return $candidates->sort(function (CartPromotion $a, CartPromotion $b): int {
            $classA = self::CLASS_ORDER[$a->discount->exclusivity_class->value];
            $classB = self::CLASS_ORDER[$b->discount->exclusivity_class->value];

            return [$classA, -$a->discount->priority, $a->id] <=> [$classB, -$b->discount->priority, $b->id];
        })->values()->all();
    }

    /**
     * Raw per-line discount for a single promotion against the original line
     * subtotals (order-independent); the caller caps it to the remaining balance.
     *
     * @return array<int, int>
     */
    private function rawAmounts(Discount $discount, CartPipelineContext $context): array
    {
        $lines = $this->applicableLines($discount, $context);

        if ($lines->isEmpty()) {
            return [];
        }

        if ($discount->type === DiscountType::Percentage) {
            $amounts = [];

            foreach ($lines as $line) {
                $subtotal = $context->lineSubtotals[$line->id] ?? 0;
                $amounts[$line->id] = (int) round($subtotal * $discount->value / 100);
            }

            return $amounts;
        }

        return $this->apportionFixedAmount($discount->value, $lines, $context);
    }

    /**
     * @param  Collection<int, CartLine>  $lines
     * @return array<int, int>
     */
    private function apportionFixedAmount(int $fixedAmount, Collection $lines, CartPipelineContext $context): array
    {
        $applicableSubtotal = $lines->sum(fn (CartLine $line): int => $context->lineSubtotals[$line->id] ?? 0);

        if ($applicableSubtotal === 0) {
            return [];
        }

        $fixedAmount = min($fixedAmount, $applicableSubtotal);
        $remaining = $fixedAmount;
        $lastIndex = $lines->count() - 1;
        $amounts = [];

        foreach ($lines->values() as $index => $line) {
            $subtotal = $context->lineSubtotals[$line->id] ?? 0;

            if ($index === $lastIndex) {
                $amounts[$line->id] = $remaining;

                continue;
            }

            $share = (int) round($fixedAmount * $subtotal / $applicableSubtotal);
            $remaining -= $share;
            $amounts[$line->id] = $share;
        }

        return $amounts;
    }

    /**
     * @return Collection<int, CartLine>
     */
    private function applicableLines(Discount $discount, CartPipelineContext $context): Collection
    {
        if ($discount->apply_to === DiscountApplyTo::Order->value) {
            return $context->cart->lines;
        }

        $productIds = $discount->items
            ->where('condition', DiscountCondition::ApplyTo)
            ->pluck('discountable_id')
            ->all();

        return $context->cart->lines->filter(
            fn (CartLine $line): bool => in_array($this->resolveProductId($line), $productIds, true)
        )->values();
    }

    private function resolveProductId(CartLine $line): ?int
    {
        $model = $line->purchasable;

        if ($model instanceof ProductVariant) {
            return $model->product_id;
        }

        if ($model instanceof Product) {
            return $model->id;
        }

        return null;
    }

    /**
     * Persist every promotion's computed amount, sequence and discount terms in
     * one upsert: applied promotions get their amount/sequence, all others are
     * zeroed (kept on the cart, contributing nothing). Full rows are supplied so
     * the INSERT clause is valid on every driver, but only these columns are
     * updated, and rows already at their target value are skipped.
     *
     * @param  Collection<int, CartPromotion>  $promotions
     * @param  array<int, array{amount: int, sequence: int}>  $computed
     */
    private function persistComputedAmounts(Collection $promotions, array $computed): void
    {
        $rows = [];

        foreach ($promotions as $promotion) {
            $amount = $computed[$promotion->id]['amount'] ?? 0;
            $sequence = $computed[$promotion->id]['sequence'] ?? 0;
            $type = $promotion->discount?->type;
            $value = $promotion->discount?->value;
            $campaignId = $promotion->discount?->campaign_id;

            if ($promotion->computed_amount === $amount && $promotion->sequence === $sequence && $promotion->type === $type && $promotion->value === $value && $promotion->campaign_id === $campaignId) {
                continue;
            }

            $rows[] = [
                'id' => $promotion->id,
                'cart_id' => $promotion->cart_id,
                'discount_id' => $promotion->discount_id,
                'source' => $promotion->source->value,
                'code' => $promotion->code,
                'computed_amount' => $amount,
                'sequence' => $sequence,
                'type' => $type?->value,
                'value' => $value,
                'campaign_id' => $campaignId,
            ];

            $promotion->setAttribute('computed_amount', $amount);
            $promotion->setAttribute('sequence', $sequence);
            $promotion->setAttribute('type', $type);
            $promotion->setAttribute('value', $value);
            $promotion->setAttribute('campaign_id', $campaignId);
            $promotion->syncChanges();
            $promotion->syncOriginalAttributes(['computed_amount', 'sequence', 'type', 'value', 'campaign_id']);
        }

        if ($rows !== []) {
            CartPromotion::query()->upsert($rows, ['id'], ['computed_amount', 'sequence', 'type', 'value', 'campaign_id']);
        }
    }
}
