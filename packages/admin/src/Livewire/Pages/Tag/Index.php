<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Tag;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Core\Models\ProductTag;
use Shopper\Livewire\Pages\AbstractPageComponent;

class Index extends AbstractPageComponent implements HasActions, HasForms, HasTable
{
    use InteractsWithActions;
    use InteractsWithForms;
    use InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(ProductTag::query()->latest())
            ->columns([
                TextColumn::make('name')
                    ->label(__('shopper::forms.label.name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->label(__('shopper::forms.label.slug'))
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label(__('shopper::forms.label.created_at'))
                    ->date()
                    ->sortable(),
            ])
            ->recordActions([
                Action::make('edit')
                    ->label(__('shopper::forms.actions.edit'))
                    ->icon(Untitledui::Edit03)
                    ->iconButton()
                    ->fillForm(fn (ProductTag $record): array => $record->toArray())
                    ->schema($this->tagForm(...))
                    ->modalWidth(Width::Medium)
                    ->action(function (ProductTag $record, array $data): void {
                        $record->update($data);

                        Notification::make()
                            ->title(__('shopper::notifications.update', ['item' => __('shopper::pages/tags.single')]))
                            ->success()
                            ->send();
                    })
                    ->modalSubmitActionLabel(__('shopper::forms.actions.update'))
                    ->visible(shopper()->auth()->user()->can('edit_tags')),
                Action::make('delete')
                    ->label(__('shopper::forms.actions.delete'))
                    ->icon(Untitledui::Trash03)
                    ->iconButton()
                    ->modalIcon(Untitledui::Trash03)
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (ProductTag $record): void {
                        $record->delete();

                        Notification::make()
                            ->title(__('shopper::notifications.delete', ['item' => __('shopper::pages/tags.single')]))
                            ->success()
                            ->send();
                    })
                    ->visible(shopper()->auth()->user()->can('delete_tags')),
            ])
            ->groupedBulkActions([
                DeleteBulkAction::make()
                    ->label(__('shopper::forms.actions.delete'))
                    ->requiresConfirmation()
                    ->action(function (Collection $records): void {
                        $records->each->delete();

                        Notification::make()
                            ->title(__('shopper::notifications.delete', ['item' => __('shopper::pages/tags.single')]))
                            ->success()
                            ->send();
                    })
                    ->deselectRecordsAfterCompletion(),
            ]);
    }

    public function tagForm(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->label(__('shopper::forms.label.name'))
                ->required()
                ->live(onBlur: true)
                ->afterStateUpdated(fn (?string $state, Set $set): mixed => $set('slug', Str::slug($state ?? ''))),
            TextInput::make('slug')
                ->label(__('shopper::forms.label.slug'))
                ->disabled()
                ->dehydrated()
                ->required()
                ->unique(ProductTag::class, 'slug', ignoreRecord: true),
        ]);
    }

    public function createAction(): Action
    {
        return Action::make('create')
            ->label(__('shopper::forms.actions.create'))
            ->schema($this->tagForm(...))
            ->modalWidth(Width::Medium)
            ->modalHeading(__('shopper::pages/tags.create'))
            ->action(function (array $data): void {
                ProductTag::query()->create($data);

                Notification::make()
                    ->title(__('shopper::notifications.create', ['item' => __('shopper::pages/tags.single')]))
                    ->success()
                    ->send();
            });
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.tags.index')
            ->title(__('shopper::pages/tags.menu'));
    }
}
