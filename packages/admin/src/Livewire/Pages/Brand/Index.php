<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Brand;

use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Core\Models\Contracts\Brand as BrandContract;
use Shopper\Facades\Shopper;
use Shopper\Livewire\Pages\AbstractPageComponent;
use Shopper\Traits\HandlesAuthorizationExceptions;

class Index extends AbstractPageComponent implements HasActions, HasForms, HasTable
{
    use HandlesAuthorizationExceptions;
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithTable;

    public function mount(): void
    {
        $this->authorize('browse_brands');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(resolve(BrandContract::class)::query()->latest()->orderBy('position'))
            ->columns([
                SpatieMediaLibraryImageColumn::make('Logo')
                    ->collection(config('shopper.media.storage.thumbnail_collection'))
                    ->circular()
                    ->grow(false),
                TextColumn::make('name')
                    ->label(__('shopper::forms.label.name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('website')
                    ->label(__('shopper::forms.label.website'))
                    ->placeholder('-')
                    ->formatStateUsing(fn (?string $state): View => view(
                        'shopper::livewire.tables.cells.brands.site',
                        ['state' => $state],
                    )),
                IconColumn::make('is_enabled')
                    ->label(__('shopper::forms.label.visibility'))
                    ->boolean(),
                TextColumn::make('updated_at')
                    ->label(__('shopper::forms.label.updated_at'))
                    ->date()
                    ->sortable(),
            ])
            ->reorderable('position')
            ->recordActions([
                Action::make('edit')
                    ->label(__('shopper::forms.actions.edit'))
                    ->icon(Untitledui::Edit03)
                    ->iconButton()
                    ->action(
                        fn (BrandContract $record) => $this->dispatch(
                            'openPanel',
                            component: 'shopper-slide-overs.brand-form',
                            arguments: ['brand' => $record]
                        )
                    )
                    ->authorize('edit_brands')
                    ->visible(Shopper::auth()->user()->can('edit_brands')),
                Action::make('delete')
                    ->label(__('shopper::forms.actions.delete'))
                    ->icon(Untitledui::Trash03)
                    ->iconButton()
                    ->modalIcon(Untitledui::Trash03)
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn (BrandContract $record) => $record->delete())
                    ->authorize('delete_brands')
                    ->visible(Shopper::auth()->user()->can('delete_brands')),
            ])
            ->groupedBulkActions([
                BulkAction::make('enabled')
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
                BulkAction::make('disabled')
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
                DeleteBulkAction::make()
                    ->label(__('shopper::forms.actions.delete'))
                    ->icon(Untitledui::Trash03)
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
                    ->authorize('delete_brands')
                    ->visible(Shopper::auth()->user()->can('delete_brands'))
                    ->deselectRecordsAfterCompletion(),
            ])
            ->filters([
                TernaryFilter::make('is_enabled'),
            ])
            ->persistFiltersInSession()
            ->emptyState(view('shopper::livewire.tables.empty-states.brands'));
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.brand.index')
            ->title(__('shopper::pages/brands.menu'));
    }
}
