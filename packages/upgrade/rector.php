<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Renaming\Rector\Name\RenameClassRector;
use Shopper\Upgrade\Rector\ClassRenames;

return RectorConfig::configure()
    ->withImportNames(
        importShortClasses: false,
        removeUnusedImports: true,
    )
    ->withConfiguredRule(RenameClassRector::class, ClassRenames::MAP);
