<?php

declare(strict_types=1);

namespace Tests\Admin\Features;

use Shopper\Facades\Shopper;
use Tests\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->prefix = Shopper::prefix();

        $this->asAdmin();
    }
}
