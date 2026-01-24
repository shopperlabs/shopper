<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Discount;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Core\Enum\DiscountApplyTo;
use Shopper\Core\Enum\DiscountEligibility;
use Shopper\Core\Models\Discount;
use Shopper\Livewire\Pages\AbstractPageComponent;
use Shopper\Traits\HasAuthenticated;

class Index extends AbstractPageComponent implements HasActions, HasForms, HasTable
{
    use HasAuthenticated;
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithTable;

    public function mount(): void
    {
        $this->authorize('browse_discounts');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Discount::with('zone', 'zone.currency')->latest())
            ->columns([
                TextColumn::make('code')
                    ->label(__('shopper::forms.label.code'))
                    ->badge()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->label(__('shopper::forms.label.type'))
                    ->searchable()
                    ->sortable(),
                ViewColumn::make('value')
                    ->label(__('shopper::words.amount'))
                    ->view('shopper::livewire.tables.cells.discounts.amount'),
                TextColumn::make('apply_to')
                    ->label(__('shopper::pages/discounts.applies_to'))
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->color('gray')
                    ->badge(),
                TextColumn::make('eligibility')
                    ->label(__('shopper::pages/discounts.customer_eligibility'))
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->color('gray')
                    ->badge(),
                IconColumn::make('is_active')
                    ->label(__('shopper::forms.label.status'))
                    ->boolean()
                    ->sortable(),
                ViewColumn::make('start_at')
                    ->label(__('shopper::words.date'))
                    ->toggleable()
                    ->view('shopper::livewire.tables.cells.discounts.date'),
                TextColumn::make('usage_limit')
                    ->label(__('shopper::pages/discounts.usage_limits'))
                    ->alignRight()
                    ->toggleable()
                    ->sortable(),
                TextColumn::make('total_use')
                    ->label(__('shopper::pages/discounts.total_use'))
                    ->alignRight()
                    ->sortable(),
                TextColumn::make('zone.name')
                    ->label(__('shopper::pages/settings/zones.single'))
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->toggledHiddenByDefault(),
            ])
            ->recordActions([
                Action::make('edit')
                    ->label(__('shopper::forms.actions.edit'))
                    ->icon(Untitledui::Edit03)
                    ->iconButton()
                    ->action(
                        fn (Discount $record) => $this->dispatch(
                            'openPanel',
                            component: 'shopper-slide-overs.discount-form',
                            arguments: ['discountId' => $record->id]
                        )
                    )
                    ->visible($this->getUser()->can('edit_discounts')),
                Action::make('delete')
                    ->label(__('shopper::forms.actions.delete'))
                    ->icon(Untitledui::Trash03)
                    ->iconButton()
                    ->modalIcon(Untitledui::Trash03)
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn (Discount $record) => $record->delete())
                    ->visible($this->getUser()->can('delete_discounts')),
            ])
            ->groupedBulkActions([
                DeleteBulkAction::make()
                    ->label(__('shopper::forms.actions.delete'))
                    ->icon(Untitledui::Trash03)
                    ->requiresConfirmation()
                    ->action(function (Collection $records): void {
                        $records->each->delete();

                        Notification::make()
                            ->title(
                                __('shopper::notifications.delete', [
                                    'item' => __('shopper::pages/discounts.single'),
                                ])
                            )
                            ->success()
                            ->send();
                    })
                    ->visible($this->getUser()->can('delete_discounts'))
                    ->deselectRecordsAfterCompletion(),
            ])
            ->filters([
                TernaryFilter::make('is_active'),
                SelectFilter::make('apply_to')
                    ->options(DiscountApplyTo::options()),
                SelectFilter::make('eligibility')
                    ->options(DiscountEligibility::options()),
                SelectFilter::make('zone_id')
                    ->label(__('shopper::pages/settings/zones.single'))
                    ->relationship('zone', 'name')
                    ->multiple(),
                Filter::make('start_at')
                    ->label(__('shopper::pages/discounts.start_date'))
                    ->schema([
                        DatePicker::make('start_at_from')
                            ->native(false),
                        DatePicker::make('start_at_until')
                            ->native(false),
                    ])
                    ->query(fn (Builder $query, array $data): Builder => $query
                        ->when(
                            $data['start_at_from'],
                            fn (Builder $query, mixed $date): Builder => $query->whereDate('start_at', '>=', $date),
                        )
                        ->when(
                            $data['start_at_until'],
                            fn (Builder $query, mixed $date): Builder => $query->whereDate('start_at', '<=', $date),
                        )),
            ])
            ->filtersFormColumns(2)
            ->emptyState(view('shopper::livewire.tables.empty-states.discounts'));
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.discounts.index')
            ->title(__('shopper::pages/discounts.menu'));
    }
}
