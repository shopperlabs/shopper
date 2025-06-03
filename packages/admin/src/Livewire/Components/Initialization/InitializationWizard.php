<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Initialization;

use Shopper\Livewire\Components\Initialization\Steps\StoreAddress;
use Shopper\Livewire\Components\Initialization\Steps\StoreInformation;
use Shopper\Livewire\Components\Initialization\Steps\StoreSocialLink;
use Spatie\LivewireWizard\Components\WizardComponent;

final class InitializationWizard extends WizardComponent
{
    public function steps(): array
    {
        return [
            config('shopper.components.initialize.steps.store-information'),
            config('shopper.components.initialize.steps.store-address'),
            config('shopper.components.initialize.steps.store-social-link'),
        ];
    }
}
