<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Product;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\QueryBuilder\Constraints\BooleanConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\DateConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\NumberConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\TextConstraint;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Shopper\Core\Models\Product;
use Shopper\Core\Repositories\Store\ProductRepository;
use Shopper\Livewire\Pages\AbstractPageComponent;

class Index extends AbstractPageComponent implements HasForms, HasTable
{
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
                (new ProductRepository())
                    ->with(['brand', 'variants'])
                    ->makeModel()
                    ->newQuery()
                    ->withCount(['variants'])
                    ->where('parent_id', null)
            )
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('thumbnail')
                    ->label(__('shopper::layout.forms.label.thumbnail'))
                    ->collection(config('shopper.core.storage.thumbnail_collection'))
                    ->defaultImageUrl(config('shopper.media.fallback_url')),

                Tables\Columns\TextColumn::make('name')
                    ->label(__('shopper::layout.forms.label.name'))
                    ->searchable()
                    ->toggleable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('price_amount')
                    ->label(__('shopper::layout.forms.label.price'))
                    ->currency()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_visible')
                    ->label(__('shopper::layout.forms.label.availability'))
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('brand.name')
                    ->label(__('shopper::layout.forms.label.brand'))
                    ->searchable()
                    ->toggleable()
                    ->sortable(),

                Tables\Columns\ViewColumn::make('stock')
                    ->label(__('shopper::layout.tables.stock'))
                    ->toggleable()
                    ->view('shopper::livewire.tables.cells.products.stock')
                    ->toggledHiddenByDefault(),

                Tables\Columns\TextColumn::make('sku')
                    ->label(__('shopper::layout.tables.sku'))
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->toggledHiddenByDefault(),

                Tables\Columns\TextColumn::make('published_at')
                    ->label(__('shopper::layout.forms.label.published_at'))
                    ->searchable()
                    ->dateTime()
                    ->toggleable()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('edit')
                        ->label(__('shopper::layout.forms.actions.edit'))
                        ->icon('untitledui-edit-04')
                        ->url(
                            fn (Product $record): string => route(
                                name: 'shopper.products.edit',
                                parameters: ['product' => $record]
                            ),
                        ),
                    Tables\Actions\ReplicateAction::make()
                        ->icon('untitledui-copy-dashed')
                        ->excludeAttributes(['variants_count', 'slug'])
                        ->successNotificationTitle(__('shopper::pages/products.notifications.replicated')),
                    Tables\Actions\Action::make(__('shopper::layout.forms.actions.delete'))
                        ->icon('untitledui-trash-03')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(fn (Product $record) => $record->delete()),
                ])
                    ->tooltip('Actions'),
            ])
            ->headerActions([])
            ->filters([
                QueryBuilder::make()
                    ->constraints([
                        TextConstraint::make('name'),
                        NumberConstraint::make('price_amount')
                            ->icon('untitledui-bank-note'),
                        BooleanConstraint::make('is_visible')
                            ->label(__('shopper::layout.forms.label.availability')),
                        BooleanConstraint::make('backorder'),
                        DateConstraint::make('published_at'),
                    ])
                    ->constraintPickerColumns(),
            ])
            ->deferFilters()
            ->filtersFormWidth(MaxWidth::Large);
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.products.index', [
            'total' => (new ProductRepository())->count(),
        ])
            ->title(__('shopper::layout.sidebar.products'));
    }
}
