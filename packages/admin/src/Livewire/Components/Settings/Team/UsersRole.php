<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Settings\Team;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Shopper\Core\Models\Contracts\ShopperUser;
use Shopper\Core\Models\Role;

class UsersRole extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public Role $role;

    public function table(Table $table): Table
    {
        $userModel = config('auth.providers.users.model');

        return $table
            ->query(
                $userModel::query()
                    ->with('roles')
                    ->whereHas('roles', function (Builder $query): void {
                        $query->where('name', $this->role->name);
                    })
            )
            ->columns([
                Tables\Columns\ViewColumn::make('full_name')
                    ->label(__('shopper::forms.label.full_name'))
                    ->view('shopper::livewire.tables.cells.administrators.name'),
                Tables\Columns\TextColumn::make('email')
                    ->label(__('shopper::forms.label.email'))
                    ->icon(fn (ShopperUser $record): string => $record->email_verified_at ? 'untitledui-check-verified-02' : 'untitledui-alert-circle')
                    ->iconColor(fn (ShopperUser $record): string => $record->email_verified_at ? 'success' : 'danger'),
                Tables\Columns\TextColumn::make('roles_label')
                    ->label(__('shopper::forms.label.role'))
                    ->badge(),
                Tables\Columns\TextColumn::make('id')
                    ->label(__('shopper::forms.label.access'))
                    ->color('gray')
                    ->formatStateUsing(
                        fn (ShopperUser $record): string|array|null => $record->hasRole(config('shopper.core.roles.admin'))
                        ? __('shopper::words.full')
                        : __('shopper::words.limited')
                    ),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make('delete')
                    ->label(__('shopper::forms.actions.delete'))
                    ->visible(fn (ShopperUser $record): bool => shopper()->auth()->user()->isAdmin() && ! $record->isAdmin()) // @phpstan-ignore-line
                    ->successNotificationTitle(__('shopper::notifications.users_roles.admin_deleted')),
            ])
            ->emptyStateIcon('untitledui-users')
            ->emptyStateHeading(__('shopper::words.no_users'));
    }

    public function render(): View
    {
        return view('shopper::livewire.components.settings.team.users-role');
    }
}
