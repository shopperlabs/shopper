<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Settings\Team;

use Filament\Forms\Components;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Shopper\Core\Models\Role;

#[Layout('shopper::components.layouts.setting')]
class RolePermission extends Component implements HasForms
{
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
                    ->required(),
                Components\TextInput::make('display_name')
                    ->label(__('shopper::layout.forms.label.display_name'))
                    ->placeholder('Manager')
                    ->required(),
                Components\Textarea::make('description')
                    ->label(__('shopper::layout.forms.label.description'))
                    ->rows(4)
                    ->columnSpan('full'),
            ])
            ->columns()
            ->statePath('data')
            ->model($this->role);
    }

    public function save(): void
    {
        $this->role->update($this->form->getState());

        Notification::make()
            ->body(__('shopper::notifications.actions.update', ['item' => __('shopper::words.role')]))
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.settings.team.role')
            ->title(__('shopper::pages/settings.roles_permissions.roles') . ' ~ ' . $this->role->display_name);
    }
}
