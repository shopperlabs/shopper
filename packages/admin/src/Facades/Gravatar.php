<?php

declare(strict_types=1);

namespace Shopper\Framework\Facades;

use Illuminate\Support\Facades\Facade;

class Gravatar extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'gravatar';
    }
}
