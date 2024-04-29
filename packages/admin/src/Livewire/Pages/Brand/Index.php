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
use Livewire\Attributes\On;
use Shopper\Core\Repositories\Store\BrandRepository;
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
            ->query((new BrandRepository())->makeModel()->newQuery())
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('Logo')
                    ->collection(config('shopper.core.storage.thumbnail_collection'))
                    ->circular()
                    ->defaultImageUrl(shopper_fallback_url())
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
                    ->action(
                        fn ($record) => $this->dispatch(
                            'openPanel',
                            component: 'shopper-slide-overs.brand-form',
                            arguments: ['brandId' => $record->id]
                        )
                    )
                    ->visible(auth()->user()->can('edit_brands')),
            ])
            ->groupedBulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->label(__('shopper::forms.actions.delete'))
                    ->requiresConfirmation()
                    ->action(function (Collection $records): void {
                        $records->each->delete();

                        Notification::make()
                            ->title(__('shopper::components.tables.status.delete'))
                            ->body(
                                __('shopper::notifications.delete', [
                                    'item' => __('shopper::pages/brands.single'),
                                ])
                            )
                            ->success()
                            ->send();
                    })
                    ->deselectRecordsAfterCompletion(),
                Tables\Actions\BulkAction::make('enabled')
                    ->label(__('shopper::forms.actions.enable'))
                    ->icon('untitledui-check-verified')
                    ->action(function (Collection $records): void {
                        $records->each->updateStatus();

                        Notification::make()
                            ->title(__('shopper::components.tables.status.updated'))
                            ->body(
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
                        $records->each->updateStatus(status: false);

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
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_enabled'),
            ])
            ->persistFiltersInSession();
    }

    #[On('brand-save')]
    public function render(): View
    {
        return view('shopper::livewire.pages.brand.index', [
            'total' => (new BrandRepository())->count(),
        ])->title(__('shopper::pages/brands.menu'));
    }
}
