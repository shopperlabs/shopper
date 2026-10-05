<?php

declare(strict_types=1);

namespace Tests\Cart\Stubs;

use Illuminate\Database\Eloquent\Model;
use Shopper\Core\Contracts\PreloadsPrices;
use Shopper\Core\Contracts\Priceable;
use Shopper\Core\Contracts\PriceResolver;
use Shopper\Core\Pricing\PricingContext;
use Shopper\Core\Pricing\ResolvedPrice;

final class TieredPriceResolver implements PreloadsPrices, PriceResolver
{
    public int $calls = 0;

    /** @var list<int> */
    public array $preloads = [];

    public ?PricingContext $lastContext = null;

    /** @var array<string, mixed> */
    public array $meta = [];

    public function __construct(
        public int $base = 1000,
        public bool $available = true,
        public bool $tierOnProduct = false,
    ) {}

    public function preload(iterable $priceables, PricingContext $context): void
    {
        $this->preloads[] = count(is_array($priceables) ? $priceables : iterator_to_array($priceables));
    }

    public function resolve(Priceable&Model $priceable, PricingContext $context): ?ResolvedPrice
    {
        $this->calls++;
        $this->lastContext = $context;

        if (! $this->available) {
            return null;
        }

        $quantity = $this->tierOnProduct ? $context->productQuantity() : $context->quantity;

        $amount = $this->base
            - ($context->customerId !== null ? 100 : 0)
            - ($quantity >= 10 ? 200 : 0);

        return new ResolvedPrice(
            amount: $amount,
            currencyCode: $context->currency(),
            compareAmount: $this->base + 500,
            originalAmount: $this->base,
            meta: $this->meta ?: ['tier' => $quantity >= 10 ? 10 : 1],
        );
    }
}
