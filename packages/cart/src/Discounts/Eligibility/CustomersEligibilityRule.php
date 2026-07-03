<?php

declare(strict_types=1);

namespace Shopper\Cart\Discounts\Eligibility;

use Shopper\Cart\Contracts\DiscountEligibilityRule;
use Shopper\Cart\Discounts\DiscountValidationResult;
use Shopper\Cart\Pipelines\CartPipelineContext;
use Shopper\Core\Enum\DiscountCondition;
use Shopper\Core\Enum\DiscountEligibility;
use Shopper\Core\Models\Discount;

final class CustomersEligibilityRule implements DiscountEligibilityRule
{
    public function key(): string
    {
        return DiscountEligibility::Customers->value;
    }

    public function label(): string
    {
        return __('shopper-core::enum/discount.eligibility.specific_customers');
    }

    public function description(): string
    {
        return __('shopper-core::enum/discount.eligibility.customers_description');
    }

    public function discountableType(): ?string
    {
        return config('auth.providers.users.model');
    }

    public function passes(Discount $discount, CartPipelineContext $context): DiscountValidationResult
    {
        if (! $context->cart->customer_id) {
            return new DiscountValidationResult(false, __('shopper-cart::messages.discount.requires_login'));
        }

        $isEligible = $discount->items()
            ->where('condition', DiscountCondition::Eligibility)
            ->where('discountable_type', $this->discountableType())
            ->where('discountable_id', $context->cart->customer_id)
            ->exists();

        if (! $isEligible) {
            return new DiscountValidationResult(false, __('shopper-cart::messages.discount.customer_not_eligible'));
        }

        return new DiscountValidationResult(true);
    }
}
