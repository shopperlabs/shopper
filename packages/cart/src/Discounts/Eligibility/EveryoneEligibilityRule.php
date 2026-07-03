<?php

declare(strict_types=1);

namespace Shopper\Cart\Discounts\Eligibility;

use Shopper\Cart\Contracts\DiscountEligibilityRule;
use Shopper\Cart\Discounts\DiscountValidationResult;
use Shopper\Cart\Pipelines\CartPipelineContext;
use Shopper\Core\Enum\DiscountEligibility;
use Shopper\Core\Models\Discount;

final class EveryoneEligibilityRule implements DiscountEligibilityRule
{
    public function key(): string
    {
        return DiscountEligibility::Everyone->value;
    }

    public function label(): string
    {
        return __('shopper-core::enum/discount.eligibility.everyone');
    }

    public function description(): string
    {
        return __('shopper-core::enum/discount.eligibility.everyone_description');
    }

    public function discountableType(): ?string
    {
        return null;
    }

    public function passes(Discount $discount, CartPipelineContext $context): DiscountValidationResult
    {
        return new DiscountValidationResult(true);
    }
}
