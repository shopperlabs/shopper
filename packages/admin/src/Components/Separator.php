<?php

declare(strict_types=1);

namespace Shopper\Components;

use Filament\Forms\Components\Component;

class Separator extends Component
{
    protected string $view = 'shopper::components.separator';

    public static function make(): static
    {
        return app(static::class);
    }
}
