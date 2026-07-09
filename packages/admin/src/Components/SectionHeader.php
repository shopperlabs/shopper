<?php

declare(strict_types=1);

namespace Shopper\Components;

use Closure;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Concerns\HasDescription;
use Filament\Schemas\Components\Concerns\HasHeading;
use Filament\Support\Concerns\HasIcon;
use Filament\Support\Concerns\HasIconColor;
use Filament\Support\Concerns\HasIconSize;
use Illuminate\Contracts\Support\Htmlable;

class SectionHeader extends Component
{
    use HasDescription;
    use HasHeading;
    use HasIcon;
    use HasIconColor;
    use HasIconSize;

    protected string $view = 'shopper::schemas.section-header';

    final public function __construct(string|Htmlable|Closure|null $heading)
    {
        $this->heading($heading);
    }

    public static function make(string|Htmlable|Closure|null $heading = null): static
    {
        $static = app(static::class, ['heading' => $heading]);
        $static->configure();

        return $static;
    }
}
