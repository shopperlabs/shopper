<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Brand;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Shopper\Core\Models\Contracts\Brand as BrandContract;
use Shopper\Facades\Shopper;
use Shopper\Livewire\Pages\AbstractPageComponent;

class Index extends AbstractPageComponent implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function mount(): void
    {
        $this->authorize('browse_brands');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(resolve(BrandContract::class)::query()->latest())
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('Logo')
                    ->collection(config('shopper.media.storage.thumbnail_collection'))
                    ->circular()
                    ->grow(false),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('shopper::forms.label.name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('website')
                    ->label(__('shopper::forms.label.website'))
                    ->formatStateUsing(fn (string $state): View => view(
                        'shopper::livewire.tables.cells.brands.site',
                        ['state' => $state],
                    )),
                Tables\Columns\IconColumn::make('is_enabled')
                    ->label(__('shopper::forms.label.visibility'))
                    ->boolean(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('shopper::forms.label.updated_at'))
                    ->date()
                    ->sortable(),
            ])
            ->reorderable('position')
            ->actions([
                Tables\Actions\Action::make('edit')
                    ->label(__('shopper::forms.actions.edit'))
                    ->icon('untitledui-edit-03')
                    ->iconButton()
                    ->action(
                        fn (BrandContract $record) => $this->dispatch(
                            'openPanel',
                            component: 'shopper-slide-overs.brand-form',
                            arguments: ['brand' => $record]
                        )
                    )
                    ->visible(Shopper::auth()->user()->can('edit_brands')),
                Tables\Actions\Action::make('delete')
                    ->label(__('shopper::forms.actions.delete'))
                    ->icon('untitledui-trash-03')
                    ->iconButton()
                    ->modalIcon('untitledui-trash-03')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn (BrandContract $record) => $record->delete())
                    ->visible(Shopper::auth()->user()->can('delete_brands')),
            ])
            ->groupedBulkActions([
                Tables\Actions\BulkAction::make('enabled')
                    ->label(__('shopper::forms.actions.enable'))
                    ->icon('untitledui-check-verified')
                    ->action(function (Collection $records): void {
                        $records->each->updateStatus(); // @phpstan-ignore-line

                        Notification::make()
                            ->title(
                                __('shopper::notifications.enabled', [
                                    'item' => __('shopper::pages/brands.single'),
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
                        $records->each->updateStatus(false); // @phpstan-ignore-line

                        Notification::make()
                            ->title(__('shopper::components.tables.status.updated'))
                            ->body(
                                __('shopper::notifications.disabled', [
                                    'item' => __('shopper::pages/brands.single'),
                                ])
                            )
                            ->success()
                            ->send();
                    })
                    ->deselectRecordsAfterCompletion(),
                Tables\Actions\DeleteBulkAction::make()
                    ->label(__('shopper::forms.actions.delete'))
                    ->icon('untitledui-trash-03')
                    ->requiresConfirmation()
                    ->action(function (Collection $records): void {
                        $records->each->delete();

                        Notification::make()
                            ->title(
                                __('shopper::notifications.delete', [
                                    'item' => __('shopper::pages/brands.single'),
                                ])
                            )
                            ->success()
                            ->send();
                    })
                    ->visible(Shopper::auth()->user()->can('delete_brands'))
                    ->deselectRecordsAfterCompletion(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_enabled'),
            ])
            ->persistFiltersInSession();
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.brand.index')
            ->title(__('shopper::pages/brands.menu'));
    }
}
