<?php

declare(strict_types=1);

namespace Shopper\Cart\Exceptions;

use Illuminate\Database\Eloquent\Model;
use RuntimeException;
use Shopper\Core\Pricing\QuantityRule;

final class QuantityRuleViolationException extends RuntimeException
{
    public function __construct(
        public readonly Model $purchasable,
        public readonly int $quantity,
        public readonly QuantityRule $rule,
    ) {
        parent::__construct(match (true) {
            $rule->maximum !== null && $quantity > $rule->maximum => __('shopper-cart::exceptions.quantity_rule.maximum', ['maximum' => $rule->maximum]),
            $quantity < $rule->minimum => __('shopper-cart::exceptions.quantity_rule.minimum', ['minimum' => $rule->minimum]),
            default => __('shopper-cart::exceptions.quantity_rule.increment', ['increment' => $rule->increment]),
        });
    }

    /**
     * @return array{purchasable_id: mixed, quantity: int, minimum: int, maximum: int|null, increment: int}
     */
    public function context(): array
    {
        return [
            'purchasable_id' => $this->purchasable->getAttribute('public_id'),
            'quantity' => $this->quantity,
            ...$this->rule->toArray(),
        ];
    }
}
