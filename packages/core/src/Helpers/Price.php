<?php

declare(strict_types=1);

namespace Shopper\Core\Helpers;

use Shopper\Core\Traits\HasPrice;

class Price
{
    use HasPrice;

    public int $amount;

    public string $formatted;

    public string $currency;

    public function __construct(public int $cent)
    {
        $this->amount = $cent / 100;
        $this->currency = shopper_currency();
        $this->formatted = $this->formattedPrice($this->amount);
    }

    public static function from(int $cent): self
    {
        return new self($cent);
    }
}
