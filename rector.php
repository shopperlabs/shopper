<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([
        __DIR__.'/packages',
        __DIR__.'/tests',
    ])
    ->withTypeCoverageLevel(8)
    ->withDeadCodeLevel(0)
    ->withCodeQualityLevel(0);
