<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Category;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\On;
use Shopper\Core\Repositories\CategoryRepository;
use Shopper\Livewire\Pages\AbstractPageComponent;

class Index extends AbstractPageComponent implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function mount(): void
    {
        $this->authorize('browse_categories');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                (new CategoryRepository)
                    ->with('parent:id,name')
                    ->query()
            )
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('image')
                    ->collection(config('shopper.core.storage.thumbnail_collection'))
                    ->circular()
                    ->defaultImageUrl(shopper_fallback_url())
                    ->grow(false),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('shopper::forms.label.name'))
                    ->formatStateUsing(
                        fn ($record) => view('shopper::livewire.tables.cells.categories.name', [
                            'category' => $record,
                        ])
                    )
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->label(__('shopper::forms.label.slug'))
                    ->description(__('shopper::words.slug_description'))
                    ->badge()
                    ->color('gray'),
                Tables\Columns\IconColumn::make('is_enabled')
                    ->label(__('shopper::forms.label.visibility'))
                    ->boolean(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('shopper::forms.label.updated_at'))
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_enabled'),
            ])
            ->actions([
                Tables\Actions\Action::make('edit')
                    ->label(__('shopper::forms.actions.edit'))
                    ->action(
                        fn ($record) => $this->dispatch(
                            'openPanel',
                            component: 'shopper-slide-overs.category-form',
                            arguments: ['categoryId' => $record->id]
                        )
                    )
                    ->visible(auth()->user()->can('edit_categories')),
            ])
            ->groupedBulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->label(__('shopper::forms.actions.delete'))
                    ->requiresConfirmation()
                    ->action(function (Collection $records): void {
                        $records->each->delete();

                        Notification::make()
                            ->title(
                                __('shopper::notifications.delete', [
                                    'item' => __('shopper::pages/categories.single'),
                                ])
                            )
                            ->success()
                            ->send();
                    })
                    ->visible(auth()->user()->can('delete_categories'))
                    ->deselectRecordsAfterCompletion(),
                Tables\Actions\BulkAction::make('enabled')
                    ->label(__('shopper::forms.actions.enable'))
                    ->icon('untitledui-check-verified')
                    ->action(function (Collection $records): void {
                        $records->each->updateStatus(); // @phpstan-ignore-line

                        Notification::make()
                            ->title(
                                __('shopper::notifications.enabled', [
                                    'item' => __('shopper::pages/categories.single'),
                                ])
                            )
                            ->success()
                            ->send();
                    })
                    ->deselectRecordsAfterCompletion(),
                Tables\Actions\BulkAction::make('disabled')
                    ->label(__('shopper::forms.actions.disable'))
                    ->icon('untitledui-slash-circle-01')
                    ->action(function (Collection $records): void {
                        $records->each->updateStatus(status: false); // @phpstan-ignore-line

                        Notification::make()
                            ->title(
                                __('shopper::notifications.disabled', [
                                    'item' => __('shopper::pages/categories.single'),
                                ])
                            )
                            ->success()
                            ->send();
                    })
                    ->deselectRecordsAfterCompletion(),
            ])
            ->persistFiltersInSession()
            ->headerActions([
                Tables\Actions\Action::make('reorder')
                    ->label(__('shopper::words.reorder'))
                    ->icon('untitledui-switch-vertical')
                    ->color('gray')
                    ->action(
                        fn ($record) => $this->dispatch(
                            'openPanel',
                            component: 'shopper-slide-overs.re-order-categories'
                        )
                    ),
            ]);
    }

    #[On('category-save')]
    public function render(): View
    {
        return view('shopper::livewire.pages.category.index')
            ->title(__('shopper::pages/categories.menu'));
    }
}
