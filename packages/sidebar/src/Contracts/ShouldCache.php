<?php

declare(strict_types=1);

namespace Shopper\Sidebar\Contracts;

use Serializable;

interface ShouldCache extends Serializable
{
    public function getCacheables(): array;
}
