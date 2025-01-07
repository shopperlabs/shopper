<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Products;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\On;
use Livewire\Component;
use Shopper\Core\Models\Price;

class Pricing extends Component implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public Model $model;

    public function table(Table $table): Table
    {
        return $table
            ->relationship(fn () => $this->model->prices()) // @phpstan-ignore-line
            ->columns([
                Tables\Columns\TextColumn::make('currency.name')
                    ->label(__('shopper::forms.label.currency'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label(__('shopper::forms.label.price'))
                    ->money(fn ($record) => $record->currencyCode),
                Tables\Columns\TextColumn::make('compare_amount')
                    ->label(__('shopper::forms.label.compare_price'))
                    ->money(fn ($record) => $record->currencyCode),
                Tables\Columns\TextColumn::make('cost_amount')
                    ->label(__('shopper::forms.label.cost_per_item'))
                    ->money(fn ($record) => $record->currencyCode),
            ])
            ->actions([
                Tables\Actions\Action::make('edit')
                    ->label(__('shopper::forms.actions.edit'))
                    ->icon('untitledui-edit-04')
                    ->action(
                        fn (Price $record) => $this->dispatch(
                            'openPanel',
                            component: 'shopper-slide-overs.manage-pricing',
                            arguments: [
                                'modelId' => $this->model->id, // @phpstan-ignore-line
                                'modelType' => get_class($this->model),
                                'currencyId' => $record->currency->id,
                            ]
                        )
                    ),
                Tables\Actions\DeleteAction::make()
                    ->label(__('shopper::forms.actions.delete'))
                    ->icon('untitledui-trash-03'),
            ])
            ->headerActions([
                Tables\Actions\Action::make('add')
                    ->label(__('shopper::pages/products.pricing.add'))
                    ->color('gray')
                    ->action(
                        fn () => $this->dispatch(
                            'openPanel',
                            component: 'shopper-slide-overs.manage-pricing',
                            arguments: [
                                'modelId' => $this->model->id, // @phpstan-ignore-line
                                'modelType' => get_class($this->model),
                            ]
                        )
                    )
                    ->visible($this->model->prices->count() !== count(shopper_setting('currencies'))), // @phpstan-ignore-line
            ])
            ->emptyStateHeading(__('shopper::pages/products.pricing.empty'))
            ->emptyStateIcon('untitledui-coins-stacked-02');
    }

    #[On('product.pricing.manage')]
    public function render(): View
    {
        return view('shopper::livewire.components.products.pricing');
    }
}
