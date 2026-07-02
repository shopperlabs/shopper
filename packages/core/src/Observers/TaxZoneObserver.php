<?php

declare(strict_types=1);

namespace Shopper\Core\Observers;

use Illuminate\Support\Facades\Cache;
use Shopper\Core\Models\TaxZone;

final class TaxZoneObserver
{
    public function saved(TaxZone $taxZone): void
    {
        $this->bumpZoneCacheVersion();
    }

    public function deleted(TaxZone $taxZone): void
    {
        $this->bumpZoneCacheVersion();
    }

    private function bumpZoneCacheVersion(): void
    {
        Cache::increment('shopper.tax-zones.version');
    }
}
