<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Shopper\Traits\AuthorizesSettingsAccess;

#[Layout('shopper::components.layouts.base')]
final class Initialization extends Component
{
    use AuthorizesSettingsAccess;

    public function mount(): void
    {
        $this->authorizeSettingsAccess();
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.initialization')
            ->title(__('shopper::pages/onboarding.title'));
    }
}
