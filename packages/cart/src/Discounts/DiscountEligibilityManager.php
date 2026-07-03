<?php

declare(strict_types=1);

namespace Shopper\Cart\Discounts;

use Shopper\Cart\Contracts\DiscountEligibilityRule;

final class DiscountEligibilityManager
{
    /** @var array<string, DiscountEligibilityRule> */
    private array $rules = [];

    public function register(DiscountEligibilityRule $rule): void
    {
        $this->rules[$rule->key()] = $rule;
    }

    public function for(string $key): ?DiscountEligibilityRule
    {
        return $this->rules[$key] ?? null;
    }

    /**
     * @return array<string, DiscountEligibilityRule>
     */
    public function all(): array
    {
        return $this->rules;
    }

    /**
     * @return array<string, string>
     */
    public function options(): array
    {
        return array_map(fn (DiscountEligibilityRule $rule): string => $rule->label(), $this->rules);
    }

    /**
     * @return array<string, string>
     */
    public function descriptions(): array
    {
        return array_map(fn (DiscountEligibilityRule $rule): string => $rule->description(), $this->rules);
    }
}
