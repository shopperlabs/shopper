<?php

declare(strict_types=1);

namespace Tests\Core\Workflows\Webhooks\Stubs;

use Shopper\Core\Contracts\WebhookPayloadSerializer;

final class InvoicePayloadSerializer implements WebhookPayloadSerializer
{
    public function serialize(object $event): array
    {
        return [
            'resource_type' => 'invoice',
            'resource_id' => $event->number,
            'data' => ['number' => $event->number],
        ];
    }
}
