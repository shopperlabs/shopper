<?php

declare(strict_types=1);

namespace Shopper\Components;

use Filament\Schemas\Components\Wizard;

class OnboardingWizard extends Wizard
{
    protected string $view = 'shopper::filament.onboarding-wizard';
}
