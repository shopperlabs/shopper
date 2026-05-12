<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Settings\Team;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Support\Enums\Size;
use Filament\Support\Enums\Width;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Livewire\Concerns\WithSettingsBreadcrumbs;
use Shopper\Models\Role;
use Shopper\Sidebar\Breadcrumbs\Breadcrumb;
use Shopper\Traits\HandlesAuthorizationExceptions;

#[Layout('shopper::components.layouts.setting')]
class Index extends Component implements HasActions, HasSchemas
{
    use HandlesAuthorizationExceptions;
    use InteractsWithActions;
    use InteractsWithSchemas;
    use WithSettingsBreadcrumbs;

    public function mount(): void
    {
        $this->authorize('system.users');
    }

    public function settingsPageBreadcrumbs(): array
    {
        return [
            new Breadcrumb(text: __('shopper::pages/settings/staff.title')),
        ];
    }

    public function createRoleAction(): Action
    {
        return Action::make('createRole')
            ->label(__('shopper::pages/settings/staff.new_role'))
            ->icon(Untitledui::Plus)
            ->iconButton()
            ->outlined()
            ->size(Size::ExtraSmall)
            ->authorize('system.settings')
            ->modalWidth(Width::Medium)
            ->modalHeading(__('shopper::modals.roles.new'))
            ->modalDescription(__('shopper::modals.roles.new_description'))
            ->modalSubmitActionLabel(__('shopper::forms.actions.save'))
            ->schema([
                TextInput::make('name')
                    ->label(__('shopper::modals.roles.labels.name'))
                    ->placeholder('manager')
                    ->unique(table: Role::class, column: 'name')
                    ->required(),
                TextInput::make('display_name')
                    ->label(__('shopper::forms.label.display_name'))
                    ->placeholder('Manager'),
                Textarea::make('description')
                    ->label(__('shopper::forms.label.description'))
                    ->rows(3)
                    ->columnSpan('full'),
            ])
            ->action(function (array $data): void {
                Role::create($data);

                Notification::make()
                    ->title(__('shopper::notifications.users_roles.role_added'))
                    ->success()
                    ->send();
            });
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.settings.team.index', [
            'roles' => Role::query()
                ->with('users')
                ->where('name', '<>', config('shopper.admin.roles.user'))
                ->orderBy('created_at')
                ->get(),
        ])
            ->title(__('shopper::pages/settings/staff.title'));
    }
}
