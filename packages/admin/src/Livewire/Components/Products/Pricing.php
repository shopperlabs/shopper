<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Products;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\DeleteAction;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\On;
use Livewire\Component;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Core\Models\Price;

class Pricing extends Component implements HasActions, HasForms, HasTable
{
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithTable;

    public Model $model;

    public function table(Table $table): Table
    {
        return $table
            ->relationship(fn () => $this->model->prices()) // @phpstan-ignore-line
            ->columns([
                TextColumn::make('currency.name')
                    ->label(__('shopper::forms.label.currency'))
                    ->sortable(),
                TextColumn::make('amount')
                    ->label(__('shopper::forms.label.price'))
                    ->money(fn ($record) => $record->currencyCode),
                TextColumn::make('compare_amount')
                    ->label(__('shopper::forms.label.compare_price'))
                    ->money(fn ($record) => $record->currencyCode),
                TextColumn::make('cost_amount')
                    ->label(__('shopper::forms.label.cost_per_item'))
                    ->money(fn ($record) => $record->currencyCode),
            ])
            ->recordActions([
                Action::make('edit')
                    ->label(__('shopper::forms.actions.edit'))
                    ->icon(Untitledui::Edit03)
                    ->iconButton()
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
                DeleteAction::make()
                    ->label(__('shopper::forms.actions.delete'))
                    ->icon(Untitledui::Trash03)
                    ->iconButton(),
            ])
            ->headerActions([
                Action::make('add')
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
