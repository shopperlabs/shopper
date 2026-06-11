<?php

declare(strict_types=1);

namespace Shopper\Api\Http\Resources;

use Illuminate\Http\Request;
use Shopper\Cart\Models\CartLine;
use Shopper\Cart\Models\CartLineAdjustment;
use Shopper\Cart\Models\CartLineTaxLine;
use Shopper\Core\Models\Contracts\ProductVariant;
use TiMacDonald\JsonApi\JsonApiResource as BaseJsonApiResource;

/**
 * @mixin CartLine
 */
final class CartLineResource extends JsonApiResource
{
    public function toType(Request $request): string
    {
        return 'cart-lines';
    }

    public function toAttributes(Request $request): array
    {
        $subtotal = $this->unit_price_amount * $this->quantity;

        return [
            'purchasable_type' => $this->purchasable instanceof ProductVariant ? 'variant' : 'product',
            'quantity' => $this->quantity,
            'unit_price_amount' => $this->unit_price_amount,
            'subtotal' => $subtotal,
            'discount_total' => (int) $this->adjustments->sum('amount'),
            'tax_total' => (int) $this->taxLines->sum('amount'),
            'adjustments' => $this->adjustmentsPayload(),
            'tax_lines' => $this->taxLinesPayload(),
            'metadata' => $this->metadata,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }

    public function toRelationships(Request $request): array
    {
        return [
            'purchasable' => fn (): BaseJsonApiResource => $this->purchasable instanceof ProductVariant
                ? ProductVariantResource::make($this->purchasable)
                : ProductResource::make($this->purchasable),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function adjustmentsPayload(): array
    {
        return $this->adjustments->map(fn (CartLineAdjustment $adjustment): array => [
            'amount' => (int) $adjustment->amount,
            'code' => $adjustment->code,
        ])->values()->all();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function taxLinesPayload(): array
    {
        return $this->taxLines->map(fn (CartLineTaxLine $taxLine): array => [
            'code' => $taxLine->code,
            'name' => $taxLine->name,
            'rate' => (float) $taxLine->rate,
            'amount' => (int) $taxLine->amount,
        ])->values()->all();
    }
}
