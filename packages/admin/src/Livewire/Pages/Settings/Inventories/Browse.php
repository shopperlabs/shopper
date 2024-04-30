<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Settings\Inventories;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Shopper\Core\Models\Inventory;

#[Layout('shopper::components.layouts.setting')]
class Browse extends Component implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    public function mount(): void
    {
        $this->authorize('browse_inventories');
    }

    public function removeAction(): Action
    {
        return Action::make('remove')
            ->iconButton()
            ->icon('untitledui-trash-03')
            ->color('danger')
            ->requiresConfirmation()
            ->action(function (array $arguments): void {
                Inventory::query()->find($arguments['id'])->delete();

                Notification::make()
                    ->title(__('shopper::notifications.inventory.removed'))
                    ->success()
                    ->send();

                $this->dispatch('$refresh');
            })
            ->visible(shopper()->auth()->user()->can('delete_inventories'));
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.settings.inventories.browse', [
            'inventories' => Inventory::query()
                ->with('country')
                ->get()
                ->take(config('shopper.admin.inventory-limit')),
        ])->title(__('shopper::pages/settings/global.location.menu'));
    }
}
