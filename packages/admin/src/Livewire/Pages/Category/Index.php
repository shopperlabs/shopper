<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Category;

use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\DeleteBulkAction;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
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
use Shopper\Core\Models\Contracts\Category;
use Shopper\Livewire\Pages\AbstractPageComponent;
use Shopper\Traits\HandlesAuthorizationExceptions;
use Shopper\Traits\HasAuthenticated;

class Index extends AbstractPageComponent implements HasActions, HasSchemas, HasTable
{
    use HandlesAuthorizationExceptions;
    use HasAuthenticated;
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public function mount(): void
    {
        $this->authorize('browse_categories');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(resolve(Category::class)::query()->with('parent')->latest())
            ->columns([
                SpatieMediaLibraryImageColumn::make('image')
                    ->collection(config('shopper.media.storage.thumbnail_collection'))
                    ->circular()
                    ->grow(false),
                TextColumn::make('name')
                    ->label(__('shopper::forms.label.name'))
                    ->formatStateUsing(
                        fn (Category $record): View => view('shopper::livewire.tables.cells.categories.name', [
                            'category' => $record,
                        ])
                    )
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->label(__('shopper::forms.label.slug'))
                    ->badge()
                    ->color('gray'),
                IconColumn::make('is_enabled')
                    ->label(__('shopper::forms.label.visibility'))
                    ->boolean(),
                TextColumn::make('updated_at')
                    ->label(__('shopper::forms.label.updated_at'))
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_enabled'),
            ])
            ->recordActions([
                Action::make('edit')
                    ->label(__('shopper::forms.actions.edit'))
                    ->icon(Untitledui::Edit03)
                    ->iconButton()
                    ->action(
                        fn (Category $record) => $this->dispatch(
                            'openPanel',
                            component: 'shopper-slide-overs.category-form',
                            arguments: ['category' => $record]
                        )
                    )
                    ->authorize('edit_categories')
                    ->visible($this->getUser()->can('edit_categories')),
                Action::make('delete')
                    ->label(__('shopper::forms.actions.delete'))
                    ->icon(Untitledui::Trash03)
                    ->iconButton()
                    ->modalIcon(Untitledui::Trash03)
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn (Category $record) => $record->delete())
                    ->authorize('delete_categories')
                    ->visible($this->getUser()->can('delete_categories')),
            ])
            ->groupedBulkActions([
                BulkAction::make('enabled')
                    ->label(__('shopper::forms.actions.enable'))
                    ->icon(Untitledui::CheckVerified)
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
                BulkAction::make('disabled')
                    ->label(__('shopper::forms.actions.disable'))
                    ->icon(Untitledui::SlashCircle01)
                    ->action(function (Collection $records): void {
                        $records->each->updateStatus(false); // @phpstan-ignore-line

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
                DeleteBulkAction::make()
                    ->label(__('shopper::forms.actions.delete'))
                    ->icon(Untitledui::Trash03)
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
                    ->authorize('delete_categories')
                    ->visible($this->getUser()->can('delete_categories'))
                    ->deselectRecordsAfterCompletion(),
            ])
            ->persistFiltersInSession()
            ->emptyState(view('shopper::livewire.tables.empty-states.categories'))
            ->headerActions([
                Action::make('reorder')
                    ->label(__('shopper::words.reorder'))
                    ->icon(Untitledui::SwitchVertical)
                    ->color('gray')
                    ->action(
                        fn () => $this->dispatch(
                            'openPanel',
                            component: 'shopper-slide-overs.re-order-categories'
                        )
                    ),
            ]);
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.category.index')
            ->title(__('shopper::pages/categories.menu'));
    }
}
