<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Settings;

use Illuminate\Contracts\View\View;
use Shopper\Livewire\Pages\AbstractPageComponent;
use Shopper\Traits\HandlesAuthorizationExceptions;

class Index extends AbstractPageComponent
{
    use HandlesAuthorizationExceptions;

    public function mount(): void
    {
        $this->authorize('system.settings');
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.settings.index')
            ->title(__('shopper::pages/settings/global.menu'));
    }
}
