<?php

namespace Shopper\Helpers;

use Shopper\Framework\Models\Traits\HasPrice;

class Price
{
    use HasPrice;

    public int $cent;
    public int $amount;
    public string $formatted;
    public string $currency;

    public function __construct(int $cent)
    {
        $this->cent = $cent;
        $this->amount = $cent / 100;
        $this->currency = shopper_currency();
        $this->formatted = $this->formattedPrice($this->amount);
    }

    public static function from(int $cent): self
    {
        return new self($cent);
    }
}
