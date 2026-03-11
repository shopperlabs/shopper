<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Settings\Locations;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Core\Models\Contracts\Inventory;
use Shopper\Traits\HandlesAuthorizationExceptions;

#[Layout('shopper::components.layouts.setting')]
class Index extends Component implements HasActions, HasSchemas
{
    use HandlesAuthorizationExceptions;
    use InteractsWithActions;
    use InteractsWithSchemas;

    public function mount(): void
    {
        $this->authorize('browse_inventories');
    }

    public function removeAction(): Action
    {
        return Action::make('remove')
            ->iconButton()
            ->icon(Untitledui::Trash03)
            ->color('danger')
            ->requiresConfirmation()
            ->action(function (array $arguments): void {
                resolve(Inventory::class)::query()->find($arguments['id'])->delete();

                Notification::make()
                    ->title(__('shopper::notifications.delete', ['item' => __('shopper::pages/settings/global.location.single')]))
                    ->success()
                    ->send();

                $this->dispatch('$refresh');
            })
            ->authorize('delete_inventories')
            ->visible(shopper()->auth()->user()->can('delete_inventories'));
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.settings.locations.index', [
            'inventories' => resolve(Inventory::class)::query()
                ->with('country')
                ->limit(config('shopper.admin.inventory_limit'))
                ->get(),
        ])->title(__('shopper::pages/settings/global.location.menu'));
    }
}
