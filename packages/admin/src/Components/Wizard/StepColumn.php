<?php

declare(strict_types=1);

namespace Shopper\Components\Wizard;

use Filament\Schemas\Components\Wizard\Step;

class StepColumn extends Step
{
    protected string $view = 'shopper::filament.wizard-column';
}
