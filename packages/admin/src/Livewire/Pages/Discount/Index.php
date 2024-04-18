<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Discount;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Shopper\Core\Enum\DiscountApplyTo;
use Shopper\Core\Enum\DiscountEligibility;
use Shopper\Core\Models\Discount;
use Shopper\Livewire\Pages\AbstractPageComponent;

class Index extends AbstractPageComponent implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(Discount::query())
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label(__('shopper::layout.forms.label.code'))
                    ->badge()
                    ->searchable()
                    ->sortable(),

                Tables\Columns\ViewColumn::make('value')
                    ->label(__('shopper::words.amount'))
                    ->toggleable()
                    ->view('shopper::livewire.tables.cells.discounts.amount'),

                Tables\Columns\TextColumn::make('apply_to')
                    ->label(__('shopper::pages/discounts.applies_to'))
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->color('gray')
                    ->badge(),

                Tables\Columns\TextColumn::make('eligibility')
                    ->label(__('shopper::pages/discounts.customer_eligibility'))
                    ->toggleable()
                    ->toggledHiddenByDefault()
                    ->color('gray')
                    ->badge(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('shopper::layout.forms.label.status'))
                    ->boolean()
                    ->sortable(),

                Tables\Columns\ViewColumn::make('start_at')
                    ->label(__('shopper::words.date'))
                    ->toggleable()
                    ->view('shopper::livewire.tables.cells.discounts.date'),

                Tables\Columns\TextColumn::make('usage_limit')
                    ->label(__('shopper::pages/discounts.usage_limits'))
                    ->alignRight()
                    ->toggleable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_use')
                    ->label(__('shopper::pages/discounts.total_use'))
                    ->alignRight()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('edit')
                    ->label(__('shopper::layout.forms.actions.edit'))
                    ->action(
                        fn (Discount $record) => $this->dispatch(
                            'openPanel',
                            component: 'shopper-slide-overs.discount-form',
                            arguments: ['discountId' => $record->id]
                        )
                    )
                    ->visible(auth()->user()->can('edit_discounts')),
            ])
            ->groupedBulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->label(__('shopper::layout.forms.actions.delete'))
                    ->requiresConfirmation()
                    ->action(function (Collection $records): void {
                        $records->each->delete();

                        Notification::make()
                            ->title(__('shopper::components.tables.status.delete'))
                            ->body(
                                __('shopper::components.tables.messages.delete', [
                                    'name' => mb_strtolower(__('shopper::words.discount')),
                                ])
                            )
                            ->success()
                            ->send();
                    })
                    ->visible(auth()->user()->can('delete_discounts'))
                    ->deselectRecordsAfterCompletion(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active'),
                Tables\Filters\SelectFilter::make('apply_to')
                    ->options(DiscountApplyTo::options()),
                Tables\Filters\SelectFilter::make('eligibility')
                    ->options(DiscountEligibility::options()),
                Tables\Filters\Filter::make('start_at')
                    ->label(__('shopper::pages/discounts.start_date'))
                    ->form([
                        DatePicker::make('start_at_from')
                            ->native(false),
                        DatePicker::make('start_at_until')
                            ->native(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['start_at_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('start_at', '>=', $date),
                            )
                            ->when(
                                $data['start_at_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('start_at', '<=', $date),
                            );
                    }),
            ])
            ->emptyStateIcon('heroicon-o-gift')
            ->emptyStateHeading(__('shopper::pages/discounts.empty_message'));
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.discounts.index', [
            'total' => Discount::query()->count(),
        ])
            ->title(__('shopper::layout.sidebar.discounts'));
    }
}
