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
use Shopper\Core\Models\Contracts\ShopperUser;
use Shopper\Livewire\Pages\AbstractPageComponent;

class Show extends AbstractPageComponent implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    public ShopperUser $customer;

    public function mount(int $user): void
    {
        $this->authorize('read_customers');

        $userModel = config('auth.providers.users.model');

        /** @var ShopperUser $customer */
        $customer = $userModel::query()->with(['addresses', 'orders'])->findOrFail($user);

        $this->customer = $customer;
    }

    public function deleteAction(): Action
    {
        return Action::make('delete')
            ->label(__('shopper::forms.actions.delete'))
            ->icon('untitledui-trash-03')
            ->modalIcon('untitledui-trash-03')
            ->requiresConfirmation()
            ->color('danger')
            ->action(function (): void {
                $this->customer->delete();

                Notification::make()
                    ->title(__('shopper::notifications.delete', ['item' => __('shopper::pages/customers.single')]))
                    ->success()
                    ->send();

                $this->redirectRoute(name: 'shopper.customers.index', navigate: true);
            });
    }

    public function render(): View
    {
        /** @var Model&ShopperUser $customer */
        $customer = $this->customer;

        return view('shopper::livewire.pages.customers.show')
            ->title(__('shopper::forms.actions.show_label', ['label' => $customer->full_name]));
    }
}
