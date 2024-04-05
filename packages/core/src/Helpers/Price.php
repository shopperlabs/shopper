<?php

declare(strict_types=1);

namespace Shopper\Core\Helpers;

final class Price
{
    public int | float $value;

    public string $formatted;

    public string $currency;

    public function __construct(int $cent)
    {
        $this->value = $cent;
        $this->currency = shopper_currency();
        $this->formatted = shopper_money_format(amount: $cent);
    }

    public static function from(int $cent): self
    {
        return new self($cent);
    }
}
