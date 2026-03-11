<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Shopper\Traits\HandlesAuthorizationExceptions;

class Dashboard extends AbstractPageComponent
{
    use HandlesAuthorizationExceptions;

    #[Computed]
    public function showSetupGuide(): bool
    {
        return ! shopper_setting('setup_guide_done');
    }

    #[On('setup-guide-completed')]
    public function onSetupGuideCompleted(): void
    {
        unset($this->showSetupGuide);
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.dashboard')
            ->title(__('shopper::pages/dashboard.menu'));
    }
}
