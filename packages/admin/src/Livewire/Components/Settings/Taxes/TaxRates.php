<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Settings\Taxes;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\DeleteAction;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Reactive;
use Livewire\Component;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Core\Models\TaxRate;

class TaxRates extends Component implements HasActions, HasForms, HasTable
{
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithTable;

    #[Reactive]
    public ?int $selectedTaxZoneId = null;

    public function table(Table $table): Table
    {
        return $table
            ->query(TaxRate::query()->withCount('rules')->where('tax_zone_id', $this->selectedTaxZoneId))
            ->columns([
                TextColumn::make('name')
                    ->label(__('shopper::forms.label.name'))
                    ->searchable(),
                TextColumn::make('code')
                    ->label(__('shopper::forms.label.code'))
                    ->badge()
                    ->color('gray')
                    ->placeholder('—'),
                TextColumn::make('rate')
                    ->label(__('shopper::pages/settings/taxes.rates.rate'))
                    ->suffix('%')
                    ->sortable(),
                IconColumn::make('is_default')
                    ->label(__('shopper::words.default'))
                    ->boolean(),
                TextColumn::make('rules_count')
                    ->label(__('shopper::pages/settings/taxes.overrides.targets'))
                    ->counts('rules')
                    ->badge()
                    ->color(fn (int $state): string => $state > 0 ? 'info' : 'gray')
                    ->placeholder('—'),
                IconColumn::make('is_combinable')
                    ->label(__('shopper::pages/settings/taxes.rates.combinable'))
                    ->boolean(),
            ])
            ->heading(__('shopper::pages/settings/taxes.rates.title'))
            ->headerActions([
                Action::make('addRate')
                    ->label(__('shopper::pages/settings/taxes.rates.add'))
                    ->icon(Untitledui::Plus)
                    ->action(fn () => $this->dispatch(
                        'openPanel',
                        component: 'shopper-slide-overs.tax-rate-form',
                        arguments: ['taxZoneId' => $this->selectedTaxZoneId]
                    )),
                Action::make('addOverride')
                    ->label(__('shopper::pages/settings/taxes.overrides.add'))
                    ->icon(Untitledui::SwitchVertical02)
                    ->color('gray')
                    ->action(fn () => $this->dispatch(
                        'openPanel',
                        component: 'shopper-slide-overs.tax-rate-override-form',
                        arguments: ['taxZoneId' => $this->selectedTaxZoneId]
                    )),
            ])
            ->recordActions([
                Action::make('edit')
                    ->label(__('shopper::forms.actions.edit'))
                    ->icon(Untitledui::Edit03)
                    ->iconButton()
                    ->action(fn (TaxRate $record) => $this->dispatch(
                        'openPanel',
                        component: $record->is_default
                            ? 'shopper-slide-overs.tax-rate-form'
                            : 'shopper-slide-overs.tax-rate-override-form',
                        arguments: ['taxZoneId' => $this->selectedTaxZoneId, 'taxRateId' => $record->id]
                    )),
                DeleteAction::make('delete')
                    ->label(__('shopper::forms.actions.delete'))
                    ->icon(Untitledui::Trash03)
                    ->iconButton(),
            ])
            ->emptyStateIcon(Untitledui::ReceiptCheck)
            ->emptyStateDescription(__('shopper::pages/settings/taxes.rates.empty_heading'));
    }

    public function render(): View
    {
        return view('shopper::livewire.components.settings.taxes.rates');
    }
}
