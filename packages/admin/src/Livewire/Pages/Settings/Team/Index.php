<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Settings\Team;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Shopper\Core\Models\Role;
use Shopper\Core\Repositories\UserRepository;

#[Layout('shopper::components.layouts.setting')]
class Index extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                (new UserRepository)
                    ->with('roles')
                    ->query()
                    ->scopes('administrators')
            )
            ->columns([
                Tables\Columns\ViewColumn::make('full_name')
                    ->label(__('shopper::forms.label.full_name'))
                    ->view('shopper::livewire.tables.cells.administrators.name'),

                Tables\Columns\TextColumn::make('email')
                    ->label(__('shopper::forms.label.email'))
                    ->icon(fn ($record): string => $record->email_verified_at ? 'untitledui-check-verified-02' : 'untitledui-alert-circle')
                    ->iconColor(fn ($record): string => $record->email_verified_at ? 'success' : 'danger'),

                Tables\Columns\TextColumn::make('roles_label')
                    ->label(__('shopper::forms.label.role'))
                    ->badge(),

                Tables\Columns\TextColumn::make('id')
                    ->label(__('shopper::forms.label.access'))
                    ->color('gray')
                    ->formatStateUsing(
                        fn ($record) => $record->hasRole(config('shopper.core.users.admin_role'))
                        ? __('shopper::words.full')
                        : __('shopper::words.limited')
                    ),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make('delete')
                    ->label(__('shopper::forms.actions.delete'))
                    ->visible(fn ($record) => shopper()->auth()->user()->isAdmin() && ! $record->isAdmin()) // @phpstan-ignore-line
                    ->successNotificationTitle(__('shopper::notifications.users_roles.admin_deleted')),
            ]);
    }

    #[On('teamUpdate')]
    public function render(): View
    {
        return view('shopper::livewire.pages.settings.team.index', [
            'roles' => Role::query()
                ->with('users')
                ->where('name', '<>', config('shopper.core.users.default_role'))
                ->orderBy('created_at')
                ->get(),
        ])
            ->title(__('shopper::pages/settings/staff.title'));
    }
}
