<?php

declare(strict_types=1);

use Shopper\FedEx\FedExDriver;
use Shopper\Shipping\Drivers\ManualDriver;
use Shopper\Ups\UpsDriver;
use Shopper\Usps\UspsDriver;

it('reports tracking support on the drivers that implement track()', function (): void {
    expect((new UpsDriver('id', 'secret', 'user', 'account'))->supportsTracking())->toBeTrue()
        ->and((new FedExDriver('id', 'secret', 'account'))->supportsTracking())->toBeTrue()
        ->and((new ManualDriver)->supportsTracking())->toBeFalse()
        ->and((new UspsDriver('id', 'secret'))->supportsTracking())->toBeFalse();
});
