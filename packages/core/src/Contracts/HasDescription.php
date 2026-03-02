<?php

declare(strict_types=1);

namespace Shopper\Core\Contracts;

use Illuminate\Contracts\Support\Htmlable;

interface HasDescription
{
    public function getDescription(): string|Htmlable|null;
}
