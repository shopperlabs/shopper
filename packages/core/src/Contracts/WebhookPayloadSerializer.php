<?php

declare(strict_types=1);

namespace Shopper\Core\Contracts;

interface WebhookPayloadSerializer
{
    /**
     * Build the wire payload for a domain event, at dispatch time.
     *
     * Called synchronously by `DispatchWebhooksListener` before the resource
     * can change or be deleted; the result is persisted on `WebhookEvent`
     * and posted as-is by `DeliverWebhookJob`. Implementations must only
     * emit explicitly safelisted attributes — no secrets, tokens or card
     * data. Rebind this contract to change the payload shape.
     *
     * @return array{resource_type: ?string, resource_id: ?string, data: array<string, mixed>}
     */
    public function serialize(object $event): array;
}
