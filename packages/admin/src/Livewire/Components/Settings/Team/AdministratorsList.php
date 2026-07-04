<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Settings\Team;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\On;
use Livewire\Component;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Components\Tables\UserColumn;
use Shopper\Models\Contracts\ShopperUser;
use Shopper\Models\Role;
use Shopper\Traits\HandlesAuthorizationExceptions;

class AdministratorsList extends Component implements HasActions, HasSchemas, HasTable
{
    use HandlesAuthorizationExceptions;
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public ?Role $role = null;

    public function table(Table $table): Table
    {
        $userModel = config('auth.providers.users.model');

        $query = $userModel::query()->with('roles');

        if ($this->role instanceof Role) {
            $query->whereHas('roles', function (Builder $query): void {
                $query->where('name', $this->role->name);
            });
        } else {
            $query->whereHas('roles', function (Builder $query): void {
                $query->where('name', '<>', config('shopper.admin.roles.user'));
            });
        }

        return $table
            ->query($query)
            ->columns([
                UserColumn::make('full_name')
                    ->label(__('shopper::forms.label.full_name'))
                    ->currentUserBadge(),
                TextColumn::make('roles.display_name')
                    ->label(__('shopper::forms.label.role'))
                    ->badge(),
                TextColumn::make('created_at')
                    ->label(__('shopper::pages/settings/staff.joined'))
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('last_login_at')
                    ->label(__('shopper::pages/settings/staff.last_signed_in'))
                    ->dateTime('M j \\a\\t g:iA')
                    ->placeholder('—')
                    ->sortable(),
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('copyUserId')
                        ->label(__('shopper::pages/settings/staff.copy_user_id'))
                        ->icon(Untitledui::Copy)
                        ->action(function (ShopperUser $record): void {
                            /** @var Model&ShopperUser $record */
                            $this->js("navigator.clipboard.writeText('{$record->getKey()}')");

                            Notification::make()
                                ->title(__('shopper::notifications.users_roles.user_id_copied'))
                                ->success()
                                ->send();
                        }),
                    DeleteAction::make('delete')
                        ->label(__('shopper::pages/settings/staff.delete_user'))
                        ->icon(Untitledui::Trash03)
                        ->color('danger')
                        ->authorize(fn (ShopperUser $record): bool => $this->canDelete($record))
                        ->successNotificationTitle(__('shopper::notifications.users_roles.admin_deleted')),
                ])
                    ->tooltip(__('shopper::words.actions')),
            ])
            ->checkIfRecordIsSelectableUsing(
                fn (ShopperUser $record): bool => $this->canBulkSelect($record)
            )
            ->toolbarActions([
                DeleteBulkAction::make()
                    ->label(__('shopper::forms.actions.delete'))
                    ->icon(Untitledui::Trash03)
                    ->requiresConfirmation()
                    ->authorize(fn (): bool => shopper()->auth()->user()->isAdmin()) // @phpstan-ignore-line
                    ->action(function (Collection $records): void {
                        $records->each->delete();

                        Notification::make()
                            ->title(__('shopper::notifications.users_roles.admin_deleted'))
                            ->success()
                            ->send();
                    }),
            ])
            ->emptyStateIcon(Untitledui::Users)
            ->emptyStateHeading(__('shopper::words.no_users'));
    }

    public function canBulkSelect(ShopperUser $record): bool
    {
        /** @var Model&ShopperUser $authUser */
        $authUser = shopper()->auth()->user();

        /** @var Model&ShopperUser $record */
        if ($record->getKey() === $authUser->getKey()) {
            return false;
        }

        if ($authUser->isAdmin()) {
            return ! $this->isLastAdmin($record);
        }

        if ($authUser->isManager()) {
            return ! $record->isAdmin() && ! $record->isManager();
        }

        return false;
    }

    public function canDelete(ShopperUser $record): bool
    {
        /** @var Model&ShopperUser $authUser */
        $authUser = shopper()->auth()->user();

        if (! $authUser->isAdmin()) {
            return false;
        }

        /** @var Model&ShopperUser $record */
        if ($record->getKey() === $authUser->getKey()) {
            return false;
        }

        return ! $this->isLastAdmin($record);
    }

    public function isLastAdmin(ShopperUser $record): bool
    {
        if (! $record->isAdmin()) {
            return false;
        }

        $userModel = config('auth.providers.users.model');
        $adminRoleName = config('shopper.admin.roles.admin');

        return $userModel::query()
            ->whereHas('roles', fn (Builder $query) => $query->where('name', $adminRoleName))
            ->count() <= 1;
    }

    #[On('admin.created')]
    public function render(): View
    {
        return view('shopper::livewire.components.settings.team.administrators-list');
    }
}
