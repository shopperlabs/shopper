<?php

declare(strict_types=1);

namespace Shopper\Tests\Admin\Collection;

use Shopper\Facades\Shopper;
use Shopper\Tests\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->prefix = Shopper::prefix();

        $this->asAdmin();
    }
}
