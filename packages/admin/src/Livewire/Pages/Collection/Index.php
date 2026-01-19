<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Collection;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Core\Models\Contracts\Collection as CollectionContract;
use Shopper\Facades\Shopper;
use Shopper\Livewire\Pages\AbstractPageComponent;

class Index extends AbstractPageComponent implements HasActions, HasForms, HasTable
{
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithTable;

    public function mount(): void
    {
        $this->authorize('browse_collections');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(resolve(CollectionContract::class)::query()->with('rules')->latest())
            ->columns([
                SpatieMediaLibraryImageColumn::make('image')
                    ->collection(config('shopper.media.storage.thumbnail_collection'))
                    ->circular()
                    ->defaultImageUrl(shopper_fallback_url())
                    ->grow(false),
                TextColumn::make('name')
                    ->label(__('shopper::forms.label.name'))
                    ->searchable(),
                TextColumn::make('type')
                    ->label(__('shopper::forms.label.type'))
                    ->formatStateUsing(fn (CollectionContract $record): string => $record->isAutomatic() ? __('shopper::pages/collections.automatic') : __('shopper::pages/collections.manual'))
                    ->badge()
                    ->searchable(),
                TextColumn::make('id')
                    ->label(__('shopper::pages/collections.product_conditions'))
                    ->formatStateUsing(
                        fn (CollectionContract $record): string => $record->rules->isNotEmpty() ? ucfirst($record->firstRule()) : 'N/A'
                    ),
                TextColumn::make('updated_at')
                    ->label(__('shopper::forms.label.updated_at'))
                    ->date(),
            ])
            ->recordActions([
                Action::make('edit')
                    ->label(__('shopper::forms.actions.edit'))
                    ->icon(Untitledui::Edit03)
                    ->iconButton()
                    ->url(
                        fn (CollectionContract $record): string => route(
                            name: 'shopper.collections.edit',
                            parameters: ['collection' => $record]
                        ),
                    )
                    ->visible(Shopper::auth()->user()->can('edit_collections')),
                Action::make('delete')
                    ->label(__('shopper::forms.actions.delete'))
                    ->icon(Untitledui::Trash03)
                    ->iconButton()
                    ->modalIcon(Untitledui::Trash03)
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn (CollectionContract $record) => $record->delete())
                    ->visible(Shopper::auth()->user()->can('delete_collections')),
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
                                    'item' => __('shopper::pages/collections.single'),
                                ])
                            )
                            ->success()
                            ->send();
                    })
                    ->visible(Shopper::auth()->user()->can('delete_collections'))
                    ->deselectRecordsAfterCompletion(),
            ])
            ->emptyState(view('shopper::livewire.tables.empty-states.collections'));
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.collections.browse')
            ->title(__('shopper::pages/collections.menu'));
    }
}
