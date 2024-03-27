<?php

declare(strict_types=1);

namespace Shopper\Core\Helpers;

use Shopper\Core\Traits\HasPrice;

final class Price
{
    use HasPrice;

    public int | float $value;

    public string $formatted;

    public string $currency;

    public function __construct(int $cent)
    {
        $this->value = $cent * 100;
        $this->currency = shopper_currency();
        $this->formatted = $this->formattedPrice($this->value);
    }

    public static function from(int $cent): self
    {
        return new self($cent);
    }
}
