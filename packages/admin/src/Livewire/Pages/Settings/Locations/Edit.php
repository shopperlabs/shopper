<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Settings\Locations;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Shopper\Core\Models\Inventory;
use Shopper\Livewire\Concerns\WithSettingsBreadcrumbs;
use Shopper\Sidebar\Breadcrumbs\Breadcrumb;
use Shopper\Traits\HandlesAuthorizationExceptions;

#[Layout('shopper::components.layouts.setting')]
class Edit extends Component
{
    use HandlesAuthorizationExceptions;
    use WithSettingsBreadcrumbs;

    #[Locked]
    public Inventory $inventory;

    public function settingsPageBreadcrumbs(): array
    {
        return [
            new Breadcrumb(
                text: __('shopper::pages/settings/global.location.menu'),
                url: Route::has('shopper.settings.locations')
                    ? route('shopper.settings.locations')
                    : null,
            ),
            new Breadcrumb(text: $this->inventory->name),
        ];
    }

    public function mount(): void
    {
        $this->authorize('inventories.edit');
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.settings.locations.edit')
            ->title(__('shopper::pages/settings/global.location.menu').' ~ '.$this->inventory->name);
    }
}
