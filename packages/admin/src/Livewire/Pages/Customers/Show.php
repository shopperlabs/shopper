<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Customers;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Shopper\Core\Repositories\UserRepository;
use Shopper\Livewire\Pages\AbstractPageComponent;

class Show extends AbstractPageComponent implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    public Model $customer;

    public function deleteAction(): Action
    {
        return Action::make(__('shopper::layout.forms.actions.delete'))
            ->requiresConfirmation()
            ->icon('untitledui-trash-03')
            ->modalIcon('untitledui-trash-03')
            ->color('danger')
            ->action(function (): void {
                $this->customer->delete();

                Notification::make()
                    ->title(__('shopper::components.tables.messages.delete', ['name' => __('shopper::words.customer')]))
                    ->success()
                    ->send();

                $this->redirectRoute(name: 'shopper.customers.index', navigate: true);
            });
    }

    public function mount(int $user): void
    {
        $this->authorize('read_customers');

        $this->customer = (new UserRepository())->with(['addresses', 'orders'])->getById($user);
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.customers.show')
            ->title(__('shopper::words.actions_label.show', ['name' => $this->customer->full_name]));
    }
}
