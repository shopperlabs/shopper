<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Settings;

use Filament\Actions\BulkAction;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Core\Models\Currency;
use Shopper\Traits\HandlesAuthorizationExceptions;

#[Layout('shopper::components.layouts.setting')]
class Currencies extends Component implements HasActions, HasSchemas, HasTable
{
    use HandlesAuthorizationExceptions;
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public function mount(): void
    {
        $this->authorize('access_setting');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Currency::withoutGlobalScopes()->orderBy('name'))
            ->columns([
                TextColumn::make('name')
                    ->label(__('shopper::forms.label.name'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('code')
                    ->label(__('shopper::pages/settings/currencies.code'))
                    ->badge()
                    ->color('gray')
                    ->searchable(),
                TextColumn::make('symbol')
                    ->label(__('shopper::pages/settings/currencies.symbol')),
                TextColumn::make('exchange_rate')
                    ->label(__('shopper::pages/settings/currencies.exchange_rate'))
                    ->numeric(decimalPlaces: 4)
                    ->sortable(),
                ToggleColumn::make('is_enabled')
                    ->label(__('shopper::forms.label.status')),
            ])
            ->recordActions([
                EditAction::make('edit')
                    ->label(__('shopper::forms.actions.edit'))
                    ->icon(Untitledui::Edit03)
                    ->iconButton()
                    ->modalHeading(__('shopper::pages/settings/currencies.edit_rate'))
                    ->modalWidth(Width::Large)
                    ->schema([
                        TextInput::make('exchange_rate')
                            ->label(__('shopper::pages/settings/currencies.exchange_rate'))
                            ->helperText(__('shopper::pages/settings/currencies.exchange_rate_help'))
                            ->numeric()
                            ->step(0.0001)
                            ->minValue(0)
                            ->required(),
                    ])
                    ->successNotificationTitle(__('shopper::pages/settings/currencies.rate_updated')),
            ])
            ->filters([
                TernaryFilter::make('is_enabled')
                    ->label(__('shopper::forms.label.status')),
            ])
            ->groupedBulkActions([
                BulkAction::make('enable')
                    ->label(__('shopper::forms.actions.enable'))
                    ->icon(Untitledui::CheckVerified)
                    ->modalIcon(Untitledui::CheckVerified)
                    ->modalIconColor('success')
                    ->requiresConfirmation()
                    ->action(function (Collection $records): void {
                        Currency::withoutGlobalScopes()
                            ->whereIn('id', $records->pluck('id'))
                            ->update(['is_enabled' => true]);

                        Notification::make()
                            ->title(__('shopper::notifications.enabled', [
                                'item' => __('shopper::pages/settings/currencies.single'),
                            ]))
                            ->success()
                            ->send();
                    })
                    ->deselectRecordsAfterCompletion(),
                BulkAction::make('disable')
                    ->label(__('shopper::forms.actions.disable'))
                    ->icon(Untitledui::SlashCircle01)
                    ->requiresConfirmation()
                    ->color('warning')
                    ->action(function (Collection $records): void {
                        Currency::withoutGlobalScopes()
                            ->whereIn('id', $records->pluck('id'))
                            ->update(['is_enabled' => false]);

                        Notification::make()
                            ->title(__('shopper::notifications.disabled', [
                                'item' => __('shopper::pages/settings/currencies.single'),
                            ]))
                            ->success()
                            ->send();
                    })
                    ->deselectRecordsAfterCompletion(),
            ])
            ->emptyStateIcon(Untitledui::CurrencyDollarCircle)
            ->emptyStateDescription(__('shopper::pages/settings/currencies.no_currency'));
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.settings.currencies')
            ->title(__('shopper::pages/settings/currencies.title'));
    }
}
