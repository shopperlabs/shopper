<?php

declare(strict_types=1);

namespace Shopper\Cart\Contracts;

use Shopper\Cart\Discounts\DiscountValidationResult;
use Shopper\Cart\Pipelines\CartPipelineContext;
use Shopper\Core\Models\Discount;

interface DiscountEligibilityRule
{
    public function key(): string;

    public function label(): string;

    public function description(): string;

    public function discountableType(): ?string;

    public function passes(Discount $discount, CartPipelineContext $context): DiscountValidationResult;
}
