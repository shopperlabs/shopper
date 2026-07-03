<?php

declare(strict_types=1);

namespace Shopper\Cart\Discounts;

use Illuminate\Database\Eloquent\Builder;
use Shopper\Cart\Models\Cart;
use Shopper\Cart\Pipelines\CartPipelineContext;
use Shopper\Core\Enum\DiscountRequirement;
use Shopper\Core\Enum\DiscountType;
use Shopper\Core\Models\Discount;
use Shopper\Core\Models\OrderPromotion;

final readonly class DiscountValidator
{
    public function __construct(
        private DiscountEligibilityManager $eligibility,
    ) {}

    public function validate(Discount $discount, CartPipelineContext $context): DiscountValidationResult
    {
        if (! $discount->is_active) {
            return new DiscountValidationResult(false, __('shopper-cart::messages.discount.not_active'));
        }

        if ($discount->value <= 0) {
            return new DiscountValidationResult(false, __('shopper-cart::messages.discount.invalid_value'));
        }

        if ($discount->type === DiscountType::Percentage && $discount->value > 100) {
            return new DiscountValidationResult(false, __('shopper-cart::messages.discount.invalid_percentage'));
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

        if ($discount->campaign !== null) {
            if ($discount->campaign->currency_code !== $context->cart->currency_code) {
                return new DiscountValidationResult(false, __('shopper-cart::messages.discount.currency_mismatch'));
            }

            if ($discount->campaign->hasReachedBudget()) {
                return new DiscountValidationResult(false, __('shopper-cart::messages.discount.campaign_budget_reached'));
            }
        }

        if ($discount->usage_limit_per_user && $this->customerAlreadyRedeemed($discount, $context->cart)) {
            return new DiscountValidationResult(false, __('shopper-cart::messages.discount.already_used'));
        }

        $eligibilityRule = $this->eligibility->for($discount->eligibility);

        if ($eligibilityRule !== null) {
            $result = $eligibilityRule->passes($discount, $context);

            if (! $result->valid) {
                return $result;
            }
        }

        if ($discount->zone_id && $context->cart->zone_id !== $discount->zone_id) {
            return new DiscountValidationResult(false, __('shopper-cart::messages.discount.not_available_in_zone'));
        }

        if ($discount->type === DiscountType::FixedAmount) {
            $discountCurrency = $discount->zone_id !== null
                ? $discount->zone->currency_code
                : shopper_currency();

            if ($discountCurrency !== $context->cart->currency_code) {
                return new DiscountValidationResult(false, __('shopper-cart::messages.discount.currency_mismatch'));
            }
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

    private function customerAlreadyRedeemed(Discount $discount, Cart $cart): bool
    {
        $column = $cart->customer_id !== null ? 'customer_id' : 'email';
        $value = $cart->customer_id ?? $cart->email;

        if ($value === null) {
            return false;
        }

        return OrderPromotion::query()
            ->where('discount_id', $discount->id)
            ->whereHas('order', fn (Builder $query) => $query->where($column, $value))
            ->exists();
    }
}
