<?php

declare(strict_types=1);

namespace Shopper\Facades;

use Illuminate\Support\Facades\Facade;

class Shopper extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'shopper';
    }
}
