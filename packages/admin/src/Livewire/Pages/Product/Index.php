<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Product;

use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\QueryBuilder\Constraints\BooleanConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\DateConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\SelectConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\TextConstraint;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Core\Enum\ProductType;
use Shopper\Core\Events\Products\ProductDeleted;
use Shopper\Core\Models\Contracts\Product;
use Shopper\Feature;
use Shopper\Livewire\Pages\AbstractPageComponent;

class Index extends AbstractPageComponent implements HasActions, HasForms, HasTable
{
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithTable;

    public function mount(): void
    {
        $this->authorize('browse_products');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                resolve(Product::class)::query()
                    ->with(['brand', 'variants'])
                    ->withCount(['variants'])
                    ->latest()
            )
            ->columns([
                SpatieMediaLibraryImageColumn::make('thumbnail')
                    ->collection(config('shopper.media.storage.thumbnail_collection'))
                    ->label(__('shopper::forms.label.thumbnail'))
                    ->circular(),
                TextColumn::make('name')
                    ->label(__('shopper::forms.label.name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->label(__('shopper::forms.label.type'))
                    ->badge(),
                TextColumn::make('sku')
                    ->label(__('shopper::layout.tables.sku'))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('brand.name')
                    ->label(__('shopper::forms.label.brand'))
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->hidden(! Feature::enabled('brand')),
                ViewColumn::make('stock')
                    ->label(__('shopper::layout.tables.stock'))
                    ->view('shopper::livewire.tables.cells.products.stock')
                    ->toggleable()
                    ->toggledHiddenByDefault(),
                IconColumn::make('is_visible')
                    ->label(__('shopper::forms.label.visibility'))
                    ->toggleable(),
                TextColumn::make('published_at')
                    ->label(__('shopper::forms.label.published_at'))
                    ->dateTime()
                    ->toggleable()
                    ->sortable(),
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('edit')
                        ->label(__('shopper::forms.actions.edit'))
                        ->icon(Untitledui::Edit03)
                        ->color('primary')
                        ->action(fn (Product $record) => $this->redirectRoute(
                            name: 'shopper.products.edit',
                            parameters: ['product' => $record],
                            navigate: true
                        ))
                        ->visible(shopper()->auth()->user()->can('edit_products')),
                    Action::make(__('shopper::forms.actions.delete'))
                        ->icon(Untitledui::Trash03)
                        ->modalIcon(Untitledui::Trash03)
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(function (Product $record): void {
                            event(new ProductDeleted($record));

                            $record->delete();
                        })
                        ->visible(shopper()->auth()->user()->can('delete_products')),
                ])
                    ->tooltip('Actions'),
            ])
            ->filters([
                QueryBuilder::make()
                    ->constraints([
                        TextConstraint::make('name'),
                        SelectConstraint::make('type')
                            ->options(ProductType::class)
                            ->multiple(),
                        BooleanConstraint::make('is_visible')
                            ->label(__('shopper::forms.label.availability')),
                        DateConstraint::make('published_at'),
                    ])
                    ->constraintPickerColumns(),
            ])
            ->deferFilters()
            ->filtersFormWidth(Width::Large)
            ->emptyState(view('shopper::livewire.tables.empty-states.products'));
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.products.index')
            ->title(__('shopper::pages/products.menu'));
    }
}
