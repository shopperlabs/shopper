<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Tag;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Components\Form\SlugInput;
use Shopper\Core\Models\ProductTag;
use Shopper\Livewire\Pages\AbstractPageComponent;
use Shopper\Traits\HandlesAuthorizationExceptions;

class Index extends AbstractPageComponent implements HasActions, HasSchemas, HasTable
{
    use HandlesAuthorizationExceptions;
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public function mount(): void
    {
        $this->authorize('tags.browse');
    }

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
                    ->authorize('tags.edit')
                    ->visible(shopper()->auth()->user()->can('tags.edit')),
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
                    ->authorize('tags.delete')
                    ->visible(shopper()->auth()->user()->can('tags.delete')),
            ])
            ->groupedBulkActions([
                DeleteBulkAction::make()
                    ->authorize('tags.delete')
                    ->visible(shopper()->auth()->user()->can('tags.delete'))
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
            ])
            ->emptyState(view('shopper::livewire.tables.empty-states.tags'));
    }

    public function tagForm(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->label(__('shopper::forms.label.name'))
                ->required(),
            SlugInput::make('slug')
                ->from('name')
                ->unique(ProductTag::class, 'slug', ignoreRecord: true),
        ]);
    }

    public function createAction(): Action
    {
        return Action::make('create')
            ->authorize('tags.create')
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
