<?php

declare(strict_types=1);

namespace Tests\Admin;

abstract class PasskeysTestCase extends TestCase
{
    protected function defineEnvironment($app): void
    {
        parent::defineEnvironment($app);

        $app['config']->set('shopper.auth.passkeys_enabled', true);
    }
}
