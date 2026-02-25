<?php

declare(strict_types=1);

namespace Shopper\Core\Actions;

use Shopper\Core\Models\Contracts\Order;
use Shopper\Core\Models\Country;
use Shopper\Core\Models\OrderTaxLine;
use Shopper\Core\Taxes\OrderItemTaxAdapter;
use Shopper\Core\Taxes\TaxCalculationContext;
use Shopper\Core\Taxes\TaxCalculator;

final class CreateOrderTaxLinesAction
{
    public function __construct(
        private readonly TaxCalculator $calculator,
    ) {}

    public function execute(Order $order): void
    {
        $order->loadMissing(['shippingAddress', 'items.product']);

        $countryCode = $this->resolveCountryCode($order);

        if (! $countryCode) {
            return;
        }

        $context = new TaxCalculationContext(countryCode: $countryCode);
        $orderTaxTotal = 0;

        foreach ($order->items as $item) {
            $adapter = new OrderItemTaxAdapter($item);
            $taxLines = $this->calculator->calculate($adapter, $context);
            $itemTaxTotal = 0;

            foreach ($taxLines as $taxLine) {
                OrderTaxLine::query()->create([
                    'taxable_type' => $item->getMorphClass(),
                    'taxable_id' => $item->id,
                    'code' => $taxLine->code ?? $taxLine->name,
                    'name' => $taxLine->name,
                    'rate' => $taxLine->rate,
                    'amount' => $taxLine->amount,
                    'tax_rate_id' => $taxLine->taxRateId,
                ]);

                $itemTaxTotal += $taxLine->amount;
            }

            $item->updateQuietly(['tax_amount' => $itemTaxTotal]);
            $orderTaxTotal += $itemTaxTotal;
        }

        $order->updateQuietly(['tax_amount' => $orderTaxTotal]);
    }

    private function resolveCountryCode(Order $order): ?string
    {
        $shippingAddress = $order->shippingAddress;

        if (! $shippingAddress?->country_name) {
            return null;
        }

        return Country::query()
            ->where('name', $shippingAddress->country_name)
            ->value('cca2');
    }
}
