<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Attribute;

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
use Shopper\Components\Tables\IconColumn;
use Shopper\Core\Models\Attribute;
use Shopper\Livewire\Pages\AbstractPageComponent;

class Browse extends AbstractPageComponent implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    public function mount(): void
    {
        $this->authorize('browse_attributes');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Attribute::query())
            ->columns([
                IconColumn::make('icon')
                    ->label(__('shopper::forms.label.icon')),

                Tables\Columns\TextColumn::make('name')
                    ->label(__('shopper::forms.label.name'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('type')
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

                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('shopper::forms.label.updated_at'))
                    ->date()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('values')
                    ->label(__('shopper::pages/attributes.values.slug'))
                    ->color('gray')
                    ->icon('untitledui-dotpoints')
                    ->action(
                        fn (Attribute $record) => $this->dispatch(
                            'openPanel',
                            component: 'shopper-slide-overs.attribute-values',
                            arguments: ['attributeId' => $record->id]
                        )
                    )
                    ->visible(fn (Attribute $record) => in_array($record->type, Attribute::fieldsWithValues())),

                Tables\Actions\Action::make('edit')
                    ->label(__('shopper::forms.actions.edit'))
                    ->color('gray')
                    ->icon('untitledui-edit-04')
                    ->action(
                        fn (Attribute $record) => $this->dispatch(
                            'openPanel',
                            component: 'shopper-slide-overs.attribute-form',
                            arguments: ['attributeId' => $record->id]
                        )
                    )
                    ->visible(auth()->user()->can('edit_attributes')),
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
                                    'item' => __('shopper::pages/attributes.single'),
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
                Tables\Actions\BulkAction::make('disabled')
                    ->label(__('shopper::forms.actions.disable'))
                    ->icon('untitledui-slash-circle-01')
                    ->action(function (Collection $records): void {
                        $records->each->updateStatus(status: false); // @phpstan-ignore-line

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
                Tables\Filters\TernaryFilter::make('is_enabled')
                    ->label(__('shopper::forms.actions.enable')),
                Tables\Filters\TernaryFilter::make('is_searchable')
                    ->label(__('shopper::forms.label.is_searchable')),
                Tables\Filters\TernaryFilter::make('is_filterable')
                    ->label(__('shopper::forms.label.is_filterable')),
            ]);
    }

    #[On('attribute-save')]
    public function render(): View
    {
        return view('shopper::livewire.pages.attributes.browse', [
            'total' => Attribute::query()->count(),
        ])
            ->title(__('shopper::pages/attributes.menu'));
    }
}
