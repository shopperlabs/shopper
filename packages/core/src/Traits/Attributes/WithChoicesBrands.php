<?php

declare(strict_types=1);

namespace Shopper\Core\Traits\Attributes;

trait WithChoicesBrands
{
    public array $selectedBrand = [];

    public function updatedSelectedBrand(array $choice): void
    {
        if (count($choice) > 0 && '0' !== $choice['value']) {
            $this->brand_id = (int) $choice['value'];
        } else {
            $this->brand_id = null;
        }
    }
}
