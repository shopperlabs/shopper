<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Attribute;

use Filament\Actions\Action;
use Filament\Actions\BulkAction;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\DeleteBulkAction;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Components\Tables\IconColumn;
use Shopper\Core\Models\Attribute;
use Shopper\Livewire\Pages\AbstractPageComponent;
use Shopper\Traits\HandlesAuthorizationExceptions;

class Browse extends AbstractPageComponent implements HasActions, HasSchemas, HasTable
{
    use HandlesAuthorizationExceptions;
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public function mount(): void
    {
        $this->authorize('browse_attributes');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Attribute::query()->latest())
            ->columns([
                IconColumn::make('icon')
                    ->label(__('shopper::forms.label.icon')),
                TextColumn::make('name')
                    ->label(__('shopper::forms.label.name'))
                    ->searchable(),
                TextColumn::make('type')
                    ->label(__('shopper::forms.label.type'))
                    ->formatStateUsing(fn (Attribute $record) => $record->type_formatted)
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_enabled')
                    ->label(__('shopper::words.is_enabled'))
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_searchable')
                    ->label(__('shopper::forms.label.is_searchable'))
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_filterable')
                    ->label(__('shopper::forms.label.is_filterable'))
                    ->boolean(),
                TextColumn::make('updated_at')
                    ->label(__('shopper::forms.label.updated_at'))
                    ->date()
                    ->sortable(),
            ])
            ->recordActions([
                Action::make('values')
                    ->label(__('shopper::pages/attributes.values.slug'))
                    ->color('gray')
                    ->icon(Untitledui::Dotpoints)
                    ->action(
                        fn (Attribute $record) => $this->dispatch(
                            'openPanel',
                            component: 'shopper-slide-overs.attribute-values',
                            arguments: ['attributeId' => $record->id]
                        )
                    )
                    ->visible(fn (Attribute $record): bool => in_array($record->type, Attribute::fieldsWithValues())),
                Action::make('edit')
                    ->label(__('shopper::forms.actions.edit'))
                    ->icon(Untitledui::Edit03)
                    ->iconButton()
                    ->action(
                        fn (Attribute $record) => $this->dispatch(
                            'openPanel',
                            component: 'shopper-slide-overs.attribute-form',
                            arguments: ['attributeId' => $record->id]
                        )
                    )
                    ->authorize('edit_attributes')
                    ->visible(shopper()->auth()->user()->can('edit_attributes')),
                Action::make('delete')
                    ->label(__('shopper::forms.actions.delete'))
                    ->icon(Untitledui::Trash03)
                    ->iconButton()
                    ->modalIcon(Untitledui::Trash03)
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn (Attribute $record) => $record->delete())
                    ->authorize('delete_attributes')
                    ->visible(shopper()->auth()->user()->can('delete_attributes')),
            ])
            ->groupedBulkActions([
                DeleteBulkAction::make()
                    ->label(__('shopper::forms.actions.delete'))
                    ->requiresConfirmation()
                    ->action(function (Collection $records): void {
                        $records->each->delete();

                        Notification::make()
                            ->title(
                                __('shopper::notifications.delete', [
                                    'item' => __('shopper::pages/attributes.single'),
                                ])
                            )
                            ->success()
                            ->send();
                    })
                    ->deselectRecordsAfterCompletion(),
                BulkAction::make('enabled')
                    ->label(__('shopper::forms.actions.enable'))
                    ->icon('untitledui-check-verified')
                    ->action(function (Collection $records): void {
                        $records->each->updateStatus(); // @phpstan-ignore-line

                        Notification::make()
                            ->title(
                                __('shopper::notifications.enabled', [
                                    'item' => __('shopper::pages/attributes.single'),
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
                                    'item' => __('shopper::pages/attributes.single'),
                                ])
                            )
                            ->success()
                            ->send();
                    })
                    ->deselectRecordsAfterCompletion(),
            ])
            ->filters([
                TernaryFilter::make('is_enabled')
                    ->label(__('shopper::forms.actions.enable')),
                TernaryFilter::make('is_searchable')
                    ->label(__('shopper::forms.label.is_searchable')),
                TernaryFilter::make('is_filterable')
                    ->label(__('shopper::forms.label.is_filterable')),
            ])
            ->emptyState(view('shopper::livewire.tables.empty-states.attributes'));
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.attributes.browse')
            ->title(__('shopper::pages/attributes.menu'));
    }
}
