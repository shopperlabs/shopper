<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Resources;

use Illuminate\Http\Request;
use Shopper\Cart\Models\Cart;
use Shopper\Cart\Models\CartPromotion;
use Shopper\Cart\Pipelines\CartPipelineContext;
use TiMacDonald\JsonApi\JsonApiResourceCollection;

/**
 * @mixin Cart
 */
final class CartResource extends JsonApiResource
{
    private ?CartPipelineContext $totals = null;

    public function withTotals(CartPipelineContext $context): self
    {
        $this->totals = $context;

        return $this;
    }

    public function toType(Request $request): string
    {
        return 'carts';
    }

    public function toAttributes(Request $request): array
    {
        return [
            'currency_code' => $this->currency_code,
            'email' => $this->email,
            'promotions' => $this->promotions
                ->map(fn (CartPromotion $promotion): array => [
                    'code' => $promotion->code,
                    'amount' => $promotion->computed_amount,
                    'status' => $promotion->computed_amount > 0 ? 'applied' : 'suppressed',
                ])
                ->values()
                ->all(),
            'completed_at' => $this->completed_at?->toIso8601String(),
            'metadata' => $this->metadata,
            'lines_count' => $this->lines->count(),
            'lines_quantity' => (int) $this->lines->sum('quantity'),
            'subtotal' => $this->totals->subtotal ?? 0,
            'discount_total' => $this->totals->discountTotal ?? 0,
            'tax_total' => $this->totals->taxTotal ?? 0,
            'shipping_total' => $this->totals->shippingTotal ?? 0,
            'total' => $this->totals->total ?? 0,
            'tax_inclusive' => $this->totals->taxInclusive ?? false,
            'shipping_option_id' => $this->shipping_option_id,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }

    public function toRelationships(Request $request): array
    {
        return [
            'lines' => fn (): JsonApiResourceCollection => CartLineResource::collection($this->lines),
            'addresses' => fn (): JsonApiResourceCollection => CartAddressResource::collection($this->addresses),
            'payment_method' => fn (): ?PaymentMethodResource => $this->paymentMethod
                ? PaymentMethodResource::make($this->paymentMethod)
                : null,
        ];
    }
}
