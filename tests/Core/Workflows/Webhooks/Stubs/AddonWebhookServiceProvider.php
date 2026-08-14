<?php

declare(strict_types=1);

namespace Tests\Core\Workflows\Webhooks\Stubs;

use Illuminate\Support\ServiceProvider;
use Shopper\Core\Webhooks\Facades\Webhooks;

final class AddonWebhookServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Webhooks::register(
            SubscriptionRenewed::class,
            'subscription.renewed',
            fn (SubscriptionRenewed $event): array => [
                'resource_type' => 'subscription',
                'resource_id' => 'sub_'.$event->plan,
                'data' => ['plan' => $event->plan],
            ],
        );

        Webhooks::register(
            InvoiceGenerated::class,
            'invoice.generated',
            InvoicePayloadSerializer::class,
        );

        Webhooks::register(
            MalformedPayload::class,
            'malformed.payload',
            fn (MalformedPayload $event): array => ['data' => ['reason' => $event->reason]],
        );

        Webhooks::register(
            NullPayload::class,
            'null.payload',
            fn (NullPayload $event) => null,
        );
    }
}
