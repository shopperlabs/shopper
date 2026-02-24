<?php

declare(strict_types=1);

namespace Shopper\Core\Contracts;

use Shopper\Core\Taxes\TaxCalculationContext;
use Shopper\Core\Taxes\TaxLine;

interface TaxCalculationProvider
{
    public function identifier(): string;

    /**
     * Calculate tax lines for a given taxable item within a context.
     *
     * @return array<int, TaxLine>
     */
    public function getTaxLines(TaxableItem $item, TaxCalculationContext $context): array;
}
