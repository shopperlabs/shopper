<?php

declare(strict_types=1);

namespace Shopper\Core\Contracts;

use BackedEnum;
use Illuminate\Contracts\Support\Htmlable;

interface HasIcon
{
    public function getIcon(): string|BackedEnum|Htmlable|null;
}
