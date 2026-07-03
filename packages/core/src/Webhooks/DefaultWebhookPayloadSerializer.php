<?php

declare(strict_types=1);

namespace Shopper\Core\Webhooks;

use Illuminate\Database\Eloquent\Model;
use Shopper\Core\Contracts\WebhookPayloadSerializer;
use Shopper\Core\Models\Contracts\Order;
use Shopper\Core\Models\Contracts\Product;

final class DefaultWebhookPayloadSerializer implements WebhookPayloadSerializer
{
    /**
     * Attribute-safelist serializer: every emitted field is listed
     * explicitly per resource type. Never spread `toArray()` here — model
     * attributes added later would silently leak to third-party endpoints.
     */
    public function serialize(object $event): array
    {
        $resource = $this->resourceOf($event);

        if (! $resource instanceof Model) {
            return ['resource_type' => null, 'resource_id' => null, 'data' => []];
        }

        return [
            'resource_type' => $resource->getMorphClass(),
            'resource_id' => (string) ($resource->getAttribute('public_id') ?? $resource->getKey()),
            'data' => $this->dataFor($resource),
        ];
    }

    private function resourceOf(object $event): ?object
    {
        foreach (['order', 'product', 'customer', 'cart'] as $property) {
            if (property_exists($event, $property)) {
                return $event->{$property};
            }
        }

        return null;
    }

    /**
     * @return array<string, mixed>
     */
    private function dataFor(Model $resource): array
    {
        return match (true) {
            $resource instanceof Order => $this->orderData($resource),
            $resource instanceof Product => $this->productData($resource),
            default => $this->genericData($resource),
        };
    }

    /**
     * @param  Model&Order  $order
     * @return array<string, mixed>
     */
    private function orderData(Order $order): array
    {
        return [
            'id' => $order->getAttribute('public_id'),
            'number' => $order->getAttribute('number'),
            'status' => $order->getAttribute('status')?->value,
            'payment_status' => $order->getAttribute('payment_status')?->value,
            'shipping_status' => $order->getAttribute('shipping_status')?->value,
            'price_amount' => $order->getAttribute('price_amount'),
            'tax_amount' => $order->getAttribute('tax_amount'),
            'shipping_amount' => $order->getAttribute('shipping_amount'),
            'currency_code' => $order->getAttribute('currency_code'),
            'email' => $order->getAttribute('email'),
            'created_at' => $order->getAttribute('created_at')?->toIso8601String(),
        ];
    }

    /**
     * @param  Model&Product  $product
     * @return array<string, mixed>
     */
    private function productData(Product $product): array
    {
        return [
            'id' => $product->getAttribute('public_id'),
            'name' => $product->getAttribute('name'),
            'slug' => $product->getAttribute('slug'),
            'sku' => $product->getAttribute('sku'),
            'type' => $product->getAttribute('type')?->value,
            'is_visible' => $product->getAttribute('is_visible'),
            'published_at' => $product->getAttribute('published_at')?->toIso8601String(),
            'updated_at' => $product->getAttribute('updated_at')?->toIso8601String(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function genericData(Model $resource): array
    {
        return array_filter([
            'id' => $resource->getAttribute('public_id') ?? $resource->getKey(),
            'email' => $resource->getAttribute('email'),
            'first_name' => $resource->getAttribute('first_name'),
            'last_name' => $resource->getAttribute('last_name'),
            'created_at' => $resource->getAttribute('created_at')?->toIso8601String(),
        ], fn (mixed $value): bool => $value !== null);
    }
}
