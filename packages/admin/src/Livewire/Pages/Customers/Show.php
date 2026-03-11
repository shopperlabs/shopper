<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Customers;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Livewire\Attributes\Url;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Livewire\Pages\AbstractPageComponent;
use Shopper\Models\Contracts\ShopperUser;
use Shopper\Traits\HandlesAuthorizationExceptions;

class Show extends AbstractPageComponent implements HasActions, HasSchemas
{
    use HandlesAuthorizationExceptions;
    use InteractsWithActions;
    use InteractsWithSchemas;

    public ShopperUser $customer;

    #[Url(as: 'tab')]
    public string $activeTab = 'profile';

    public function mount(int $user): void
    {
        $this->authorize('read_customers');

        $userModel = config('auth.providers.users.model');

        /** @var ShopperUser $customer */
        $customer = $userModel::query()->with(['addresses', 'orders'])->findOrFail($user);

        $this->customer = $customer;
    }

    public function anonymizeAction(): Action
    {
        return Action::make('anonymize')
            ->label(__('shopper::pages/customers.anonymize.action'))
            ->authorize('delete_customers', $this->customer) // @phpstan-ignore-line
            ->icon(Untitledui::UserX02)
            ->modalIcon(Untitledui::UserX02)
            ->modalHeading(__('shopper::pages/customers.anonymize.title'))
            ->modalDescription(__('shopper::pages/customers.anonymize.description'))
            ->modalSubmitActionLabel(__('shopper::pages/customers.anonymize.confirm'))
            ->visible(shopper()->auth()->user()->can('delete_customers'))
            ->requiresConfirmation()
            ->color('danger')
            ->action(function (): void {
                /** @var Model&ShopperUser $customer */
                $customer = $this->customer;

                $customer->update([
                    'first_name' => __('shopper::pages/customers.anonymize.first_name'),
                    'last_name' => __('shopper::pages/customers.anonymize.last_name'),
                    'email' => 'anonymized_'.$customer->id.'_'.Str::random(8).'@anonymized.local',
                    'phone_number' => null,
                    'avatar_type' => 'gravatar',
                    'avatar_location' => null,
                    'birth_date' => null,
                    'last_login_ip' => null,
                    'opt_in' => false,
                ]);

                $customer->addresses()->delete();

                Notification::make()
                    ->title(__('shopper::pages/customers.anonymize.success'))
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
