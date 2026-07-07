<?php

declare(strict_types=1);

return [
    'title' => 'Webhooks',
    'add_webhook' => 'Add webhook',
    'empty' => 'No webhook subscriptions',
    'no_webhook' => 'No webhook subscription yet. Add one to notify an external system when events happen in your store.',
    'events' => 'Events',
    'deliveries' => 'Deliveries',
    'no_delivery' => 'No delivery yet for this webhook.',
    'attempt' => 'Attempt :number',
    'redeliver' => 'Redeliver',
    'redelivered' => 'The delivery has been queued again.',
    'redeliver_refused' => 'The resource of this event no longer exists, the payload cannot be redelivered.',
    'created' => 'Webhook created',
    'updated' => 'Webhook updated',
    'unsafe_url' => 'The webhook url must use https and resolve to a public address.',
    'regenerate_secret' => 'Regenerate secret',
    'regenerate_secret_warning' => 'The current secret stops working immediately. Update your receiving endpoint with the new one.',
    'secret_regenerated' => 'Secret regenerated',
    'secret_reveal' => 'Signing secret (copy it now, it will not be shown again): :secret',
    'cap_reached' => 'The maximum number of webhook subscriptions has been reached.',
];
