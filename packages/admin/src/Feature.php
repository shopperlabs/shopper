<?php

declare(strict_types=1);

namespace Shopper;

use Shopper\Enum\FeatureState;

final class Feature
{
    /**
     * Determine if the given feature is enabled.
     */
    public static function enabled(string $feature): bool
    {
        return config('shopper.features.' . $feature) === FeatureState::ENABLED;
    }
}
