<?php

declare(strict_types=1);

namespace Tests\Core\Workflows\Webhooks;

use Tests\Core\TestCase;
use Tests\Core\Workflows\Webhooks\Stubs\AddonWebhookServiceProvider;

abstract class WebhookRegistryTestCase extends TestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            ...parent::getPackageProviders($app),
            AddonWebhookServiceProvider::class,
        ];
    }
}
