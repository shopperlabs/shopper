<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Settings\Team;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Shopper\Core\Models\Role;

/**
 * @property Form $form
 */
#[Layout('shopper::components.layouts.setting')]
class RolePermission extends Component implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    public Role $role;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->role->toArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Components\TextInput::make('name')
                    ->label(__('shopper::modals.roles.labels.name'))
                    ->placeholder('manager')
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('display_name', Str::title($state)))
                    ->required(),
                Components\TextInput::make('display_name')
                    ->label(__('shopper::forms.label.display_name'))
                    ->placeholder('Manager')
                    ->required(),
                Components\Textarea::make('description')
                    ->label(__('shopper::forms.label.description'))
                    ->rows(4)
                    ->columnSpan('full'),
            ])
            ->columns()
            ->statePath('data')
            ->model($this->role);
    }

    public function deleteAction(): Action
    {
        return DeleteAction::make('delete')
            ->label(__('shopper::forms.actions.delete'))
            ->icon('untitledui-trash-03')
            ->visible($this->role->can_be_removed)
            ->record($this->role)
            ->successNotificationTitle(__('shopper::notifications.users_roles.role_deleted'))
            ->after(fn () => $this->redirectRoute(name: 'shopper.settings.users', navigate: true));

    }

    public function save(): void
    {
        $this->role->update($this->form->getState());

        Notification::make()
            ->body(__('shopper::notifications.update', ['item' => __('shopper::pages/settings/staff.role')]))
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.settings.team.role')
            ->title(__('shopper::pages/settings/staff.roles') . ' ~ ' . $this->role->display_name);
    }
}
