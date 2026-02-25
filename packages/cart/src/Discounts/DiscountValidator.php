<?php

declare(strict_types=1);

namespace Shopper\Cart\Discounts;

use Shopper\Cart\Pipelines\CartPipelineContext;
use Shopper\Core\Enum\DiscountCondition;
use Shopper\Core\Enum\DiscountEligibility;
use Shopper\Core\Enum\DiscountRequirement;
use Shopper\Core\Models\Discount;

final readonly class DiscountValidator
{
    public function validate(Discount $discount, CartPipelineContext $context): DiscountValidationResult
    {
        if (! $discount->is_active) {
            return new DiscountValidationResult(false, __('shopper-cart::messages.discount.not_active'));
        }

        if ($discount->start_at->isFuture()) {
            return new DiscountValidationResult(false, __('shopper-cart::messages.discount.not_started'));
        }

        if ($discount->end_at !== null && $discount->end_at->isPast()) {
            return new DiscountValidationResult(false, __('shopper-cart::messages.discount.expired'));
        }

        if ($discount->hasReachedLimit()) {
            return new DiscountValidationResult(false, __('shopper-cart::messages.discount.usage_limit_reached'));
        }

        if ($discount->usage_limit_per_user && $context->cart->customer_id) {
            $userUses = $discount->items()
                ->where('condition', DiscountCondition::Eligibility)
                ->where('discountable_type', config('auth.providers.users.model'))
                ->where('discountable_id', $context->cart->customer_id)
                ->value('total_use') ?? 0;

            if ($userUses > 0) {
                return new DiscountValidationResult(false, __('shopper-cart::messages.discount.already_used'));
            }
        }

        if ($discount->eligibility === DiscountEligibility::Customers->value) {
            if (! $context->cart->customer_id) {
                return new DiscountValidationResult(false, __('shopper-cart::messages.discount.requires_login'));
            }

            $isEligible = $discount->items()
                ->where('condition', DiscountCondition::Eligibility)
                ->where('discountable_type', config('auth.providers.users.model'))
                ->where('discountable_id', $context->cart->customer_id)
                ->exists();

            if (! $isEligible) {
                return new DiscountValidationResult(false, __('shopper-cart::messages.discount.customer_not_eligible'));
            }
        }

        if ($discount->zone_id && $context->cart->zone_id !== $discount->zone_id) {
            return new DiscountValidationResult(false, __('shopper-cart::messages.discount.not_available_in_zone'));
        }

        if ($discount->min_required === DiscountRequirement::Price->value) {
            $minAmount = (int) $discount->min_required_value;

            if ($context->subtotal < $minAmount) {
                return new DiscountValidationResult(false, __('shopper-cart::messages.discount.min_amount_not_reached'));
            }
        }

        if ($discount->min_required === DiscountRequirement::Quantity->value) {
            $totalQuantity = $context->cart->lines->sum('quantity');
            $minQuantity = (int) $discount->min_required_value;

            if ($totalQuantity < $minQuantity) {
                return new DiscountValidationResult(false, __('shopper-cart::messages.discount.min_quantity_not_reached'));
            }
        }

        return new DiscountValidationResult(true);
    }
}
