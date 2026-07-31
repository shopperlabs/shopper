<?php

declare(strict_types=1);

namespace Shopper\Http\Contracts;

use Illuminate\Http\Request;
use Shopper\Core\Models\Contracts\Channel;

interface ChannelResolver
{
    public function resolve(Request $request): ?Channel;
}
