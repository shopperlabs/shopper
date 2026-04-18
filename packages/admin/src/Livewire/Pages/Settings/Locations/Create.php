<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Settings\Locations;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Shopper\Livewire\Concerns\WithSettingsBreadcrumbs;
use Shopper\Sidebar\Breadcrumbs\Breadcrumb;
use Shopper\Traits\HandlesAuthorizationExceptions;

#[Layout('shopper::components.layouts.setting')]
class Create extends Component
{
    use HandlesAuthorizationExceptions;
    use WithSettingsBreadcrumbs;

    public function settingsPageBreadcrumbs(): array
    {
        return [
            new Breadcrumb(
                text: __('shopper::pages/settings/global.location.menu'),
                url: Route::has('shopper.settings.locations')
                    ? route('shopper.settings.locations')
                    : null,
            ),
            new Breadcrumb(text: __('shopper::forms.actions.create')),
        ];
    }

    public function mount(): void
    {
        $this->authorize('inventories.create');
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.settings.locations.create')
            ->title(__('shopper::pages/settings/global.location.add'));
    }
}
