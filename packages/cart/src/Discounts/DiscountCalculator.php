<?php

declare(strict_types=1);

namespace Shopper\Cart\Discounts;

use Illuminate\Support\Collection;
use Shopper\Cart\Models\CartLine;
use Shopper\Cart\Models\CartLineAdjustment;
use Shopper\Cart\Pipelines\CartPipelineContext;
use Shopper\Core\Enum\DiscountApplyTo;
use Shopper\Core\Enum\DiscountCondition;
use Shopper\Core\Enum\DiscountType;
use Shopper\Core\Models\Contracts\Product;
use Shopper\Core\Models\Contracts\ProductVariant;
use Shopper\Core\Models\Discount;

final readonly class DiscountCalculator
{
    public function __construct(
        private DiscountValidator $validator,
    ) {}

    public function apply(CartPipelineContext $context): void
    {
        $discount = Discount::query()
            ->where('code', $context->cart->coupon_code)
            ->where('is_active', true)
            ->first();

        if (! $discount) {
            return;
        }

        $result = $this->validator->validate($discount, $context);

        if (! $result->valid) {
            return;
        }

        CartLineAdjustment::query()
            ->whereIn('cart_line_id', $context->cart->lines->pluck('id'))
            ->delete();

        $applicableLines = $this->getApplicableLines($discount, $context);

        if ($applicableLines->isEmpty()) {
            return;
        }

        if ($discount->type === DiscountType::Percentage) {
            $context->discountTotal = $this->applyPercentage($discount, $applicableLines, $context);
        } elseif ($discount->type === DiscountType::FixedAmount) {
            $context->discountTotal = $this->applyFixedAmount($discount, $applicableLines, $context);
        }
    }

    /**
     * @return Collection<int, CartLine>
     */
    private function getApplicableLines(Discount $discount, CartPipelineContext $context): Collection
    {
        if ($discount->apply_to === DiscountApplyTo::Order->value) {
            return $context->cart->lines;
        }

        $productIds = $discount->items()
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
     * @param  Collection<int, CartLine>  $lines
     */
    private function applyPercentage(Discount $discount, Collection $lines, CartPipelineContext $context): int
    {
        $percentage = $discount->value;
        $total = 0;
        $adjustments = [];

        foreach ($lines as $line) {
            $lineSubtotal = $context->lineSubtotals[$line->id] ?? 0;
            $amount = (int) round($lineSubtotal * $percentage / 100);

            $adjustments[] = [
                'cart_line_id' => $line->id,
                'amount' => $amount,
                'code' => $discount->code,
                'discount_id' => $discount->id,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $total += $amount;
        }

        CartLineAdjustment::query()->insert($adjustments);

        return $total;
    }

    /**
     * @param  Collection<int, CartLine>  $lines
     */
    private function applyFixedAmount(Discount $discount, Collection $lines, CartPipelineContext $context): int
    {
        $fixedAmount = $discount->value;
        $applicableSubtotal = $lines->sum(fn (CartLine $line): int => $context->lineSubtotals[$line->id] ?? 0);

        if ($applicableSubtotal === 0) {
            return 0;
        }

        $fixedAmount = min($fixedAmount, $applicableSubtotal);
        $total = 0;
        $remaining = $fixedAmount;
        $lastIndex = $lines->count() - 1;
        $adjustments = [];

        foreach ($lines->values() as $index => $line) {
            $lineSubtotal = $context->lineSubtotals[$line->id] ?? 0;

            if ($index === $lastIndex) {
                $amount = $remaining;
            } else {
                $amount = (int) round($fixedAmount * $lineSubtotal / $applicableSubtotal);
                $remaining -= $amount;
            }

            $adjustments[] = [
                'cart_line_id' => $line->id,
                'amount' => $amount,
                'code' => $discount->code,
                'discount_id' => $discount->id,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $total += $amount;
        }

        CartLineAdjustment::query()->insert($adjustments);

        return $total;
    }
}
