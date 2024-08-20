<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Collection;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Shopper\Core\Repositories\CollectionRepository;
use Shopper\Livewire\Pages\AbstractPageComponent;

class Index extends AbstractPageComponent implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function mount(): void
    {
        $this->authorize('browse_collections');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                (new CollectionRepository)
                    ->makeModel()
                    ->with('rules')
                    ->newQuery()
            )
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('image')
                    ->collection(config('shopper.core.storage.thumbnail_collection'))
                    ->circular()
                    ->defaultImageUrl(shopper_fallback_url())
                    ->grow(false),

                Tables\Columns\TextColumn::make('name')
                    ->label(__('shopper::forms.label.name'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('type')
                    ->label(__('shopper::forms.label.type'))
                    ->formatStateUsing(fn ($record): string => $record->isAutomatic() ? __('shopper::pages/collections.automatic') : __('shopper::pages/collections.manual'))
                    ->badge()
                    ->searchable(),

                Tables\Columns\TextColumn::make('id')
                    ->label(__('shopper::pages/collections.product_conditions'))
                    ->formatStateUsing(function ($record): string {
                        if ($record->rules->isNotEmpty()) {
                            return ucfirst($record->firstRule());
                        }

                        return 'N/A';
                    }),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('shopper::forms.label.updated_at'))
                    ->date(),
            ])
            ->actions([
                Tables\Actions\Action::make('edit')
                    ->label(__('shopper::forms.actions.edit'))
                    ->icon('untitledui-edit-04')
                    ->url(
                        fn (Model $record): string => route(
                            name: 'shopper.collections.edit',
                            parameters: ['collection' => $record]
                        ),
                    ),

                Tables\Actions\Action::make(__('shopper::forms.actions.delete'))
                    ->icon('untitledui-trash-03')
                    ->modalIcon('untitledui-trash-03')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn ($record) => $record->delete()),
            ]);
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.collections.browse', [
            'total' => (new CollectionRepository)->count(),
        ])
            ->title(__('shopper::pages/collections.menu'));
    }
}
