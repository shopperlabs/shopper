<?php

declare(strict_types=1);

namespace Shopper\Shipping\Drivers;

use Illuminate\Support\Collection;
use Shopper\Shipping\DataTransferObjects\Address;

/**
 * Manual driver for carriers without API integration.
 * Rates are managed through carrier_options in the database.
 */
final class ManualDriver extends Driver
{
    public function code(): string
    {
        return 'manual';
    }

    public function name(): string
    {
        return 'Manual';
    }

    public function logo(): string
    {
        return shopper_panel_assets('/images/carriers/local.png');
    }

    public function isConfigured(): bool
    {
        return true;
    }

    public function supportsRealTimeRates(): bool
    {
        return false;
    }

    public function supportsLabels(): bool
    {
        return false;
    }

    public function supportsTracking(): bool
    {
        return false;
    }

    public function calculateRates(Address $from, Address $to, array $packages): Collection
    {
        // Manual driver doesn't calculate rates via API
        // Rates come from carrier_options table
        return collect();
    }
}
