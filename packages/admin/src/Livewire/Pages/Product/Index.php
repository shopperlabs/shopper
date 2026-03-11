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
use Illuminate\Contracts\Pagination\CursorPaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Core\Enum\ProductType;
use Shopper\Core\Events\Products\ProductDeleted;
use Shopper\Core\Models\Contracts\Product;
use Shopper\Core\Models\Contracts\ProductVariant;
use Shopper\Feature;
use Shopper\Livewire\Pages\AbstractPageComponent;
use Shopper\Traits\HandlesAuthorizationExceptions;

class Index extends AbstractPageComponent implements HasActions, HasForms, HasTable
{
    use HandlesAuthorizationExceptions;
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithTable {
        InteractsWithTable::getTableRecords as private baseGetTableRecords;
    }

    private bool $stockBatchLoaded = false;

    public function getTableRecords(): Collection|Paginator|CursorPaginator
    {
        $records = $this->baseGetTableRecords();

        if (! $this->stockBatchLoaded) {
            /** @var EloquentCollection<int, Product> $items */
            $items = method_exists($records, 'getCollection')
                ? $records->getCollection()
                : $records;

            if ($items->isNotEmpty()) {
                $items->first()::loadCurrentStock($items); // @phpstan-ignore-line

                /** @var EloquentCollection<int, ProductVariant> $variants */
                $variants = new EloquentCollection(); // @phpstan-ignore-line

                foreach ($items as $product) {
                    if ($product->variants_count > 0) { // @phpstan-ignore-line
                        foreach ($product->variants as $variant) {
                            $variants->push($variant);
                        }
                    }
                }

                if ($variants->isNotEmpty()) {
                    $variants->first()::loadCurrentStock($variants); // @phpstan-ignore-line
                }
            }

            $this->stockBatchLoaded = true;
        }

        return $records;
    }

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
                    ->icon(fn (Product $record): string => $record->type->getIcon())
                    ->color(fn (Product $record): string => $record->type->getColor())
                    ->formatStateUsing(fn (ProductType $state): string => $state->getLabel())
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
                    ->toggleable(),
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
                        ->authorize('edit_products')
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
                        ->authorize('delete_products')
                        ->visible(shopper()->auth()->user()->can('delete_products')),
                ])
                    ->tooltip('Actions'),
            ])
            ->filters([
                QueryBuilder::make()
                    ->constraints([
                        TextConstraint::make('name'),
                        SelectConstraint::make('type')
                            ->options(ProductType::options())
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
