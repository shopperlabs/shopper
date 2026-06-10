<?php

declare(strict_types=1);

namespace Shopper\Http\Contracts;

use Illuminate\Http\Request;
use Shopper\Core\Models\Contracts\Zone;

interface ZoneResolver
{
    public function resolve(Request $request): ?Zone;
}
