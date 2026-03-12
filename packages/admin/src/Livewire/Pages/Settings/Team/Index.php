<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Settings\Team;

use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Support\Enums\Size;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Models\Contracts\ShopperUser;
use Shopper\Models\Role;
use Shopper\Traits\HandlesAuthorizationExceptions;

#[Layout('shopper::components.layouts.setting')]
class Index extends Component implements HasActions, HasSchemas, HasTable
{
    use HandlesAuthorizationExceptions;
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public function createRoleAction(): Action
    {
        return Action::make('createRole')
            ->label(__('shopper::pages/settings/staff.new_role'))
            ->icon(Untitledui::Plus)
            ->iconButton()
            ->outlined()
            ->size(Size::Small)
            ->modalWidth(Width::Large)
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

    public function table(Table $table): Table
    {
        $userModel = config('auth.providers.users.model');

        return $table
            ->query($userModel::query()->with('roles')->scopes('administrators'))
            ->columns([
                ViewColumn::make('full_name')
                    ->label(__('shopper::forms.label.full_name'))
                    ->view('shopper::livewire.tables.cells.administrators.name'),
                TextColumn::make('email')
                    ->label(__('shopper::forms.label.email'))
                    ->icon(function (ShopperUser $record): BackedEnum {
                        /** @var Model&ShopperUser $record */
                        return $record->email_verified_at ? Untitledui::CheckVerified02 : Untitledui::AlertCircle;
                    })
                    ->iconColor(function (ShopperUser $record): string {
                        /** @var Model&ShopperUser $record */
                        return $record->email_verified_at ? 'success' : 'danger';
                    }),
                TextColumn::make('roles_label')
                    ->label(__('shopper::forms.label.role'))
                    ->badge(),
                TextColumn::make('id')
                    ->label(__('shopper::forms.label.access'))
                    ->color('gray')
                    ->formatStateUsing(
                        fn (ShopperUser $record): string|array|null => $record->hasRole(config('shopper.admin.roles.admin'))
                        ? __('shopper::words.full')
                        : __('shopper::words.limited')
                    ),
            ])
            ->recordActions([
                DeleteAction::make('delete')
                    ->icon(Untitledui::Trash03)
                    ->iconButton()
                    ->label(__('shopper::forms.actions.delete'))
                    ->visible(fn (ShopperUser $record): bool => shopper()->auth()->user()->isAdmin() && ! $record->isAdmin()) // @phpstan-ignore-line
                    ->successNotificationTitle(__('shopper::notifications.users_roles.admin_deleted')),
            ]);
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
