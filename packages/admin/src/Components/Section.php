<?php

declare(strict_types=1);

namespace Shopper\Components;

use Filament\Forms\Components\Section as BaseSection;

class Section extends BaseSection
{
    protected string $view = 'shopper::filament.section';
}
