<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Collection;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Shopper\Components\Form\SeoField;
use Shopper\Core\Models\Contracts\Collection;
use Shopper\Livewire\Components\Collection\CollectionProducts;
use Shopper\Livewire\Pages\AbstractPageComponent;

/**
 * @property-read Schema $form
 */
class Edit extends AbstractPageComponent implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    public ?Collection $collection = null;

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public function mount(): void
    {
        $this->authorize('edit_collections');

        $this->collection?->load('rules');

        $this->form->fill($this->collection?->toArray() ?? []);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make()
                    ->schema([
                        Grid::make()
                            ->schema([
                                TextInput::make('name')
                                    ->label(__('shopper::forms.label.name'))
                                    ->placeholder('Summers Collections, Christmas promotions...')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (string $operation, ?string $state, Set $set): void {
                                        if ($state) {
                                            $set('slug', Str::slug($state));
                                        }
                                    }),
                                TextInput::make('slug')
                                    ->label(__('shopper::forms.label.slug'))
                                    ->disabled()
                                    ->dehydrated()
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(config('shopper.models.collection'), 'slug', ignoreRecord: true),
                            ]),
                        RichEditor::make('description')
                            ->label(__('shopper::forms.label.description'))
                            ->toolbarButtons([
                                ['bold', 'italic', 'link', 'strike', 'underline'],
                                ['bulletList', 'orderedList', 'table', 'attachFiles'],
                                ['undo', 'redo'],
                            ]),
                        Livewire::make(CollectionProducts::class, ['collection' => $this->collection]),
                    ])
                    ->columnSpan(['lg' => 2]),
                Group::make()
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('file')
                            ->label(__('shopper::forms.label.image_preview'))
                            ->collection(config('shopper.media.storage.thumbnail_collection'))
                            ->image()
                            ->maxSize(config('shopper.media.max_size.thumbnail')),
                        DateTimePicker::make('published_at')
                            ->label(__('shopper::forms.label.availability'))
                            ->native(false)
                            ->required()
                            ->default(now())
                            ->helperText(__('shopper::pages/collections.availability_description')),
                        Group::make()
                            ->schema([
                                TextEntry::make(__('shopper::words.seo.slug'))
                                    ->label(__('shopper::words.seo.title'))
                                    ->state(new HtmlString(Blade::render(<<<'BLADE'
                                        <p class="max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                                            {{ __('shopper::words.seo.description', ['name' => __('shopper::pages/collections.single')]) }}
                                        </p>
                                    BLADE))),
                                ...SeoField::make(),
                            ]),
                        KeyValue::make('metadata')->reorderable(),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3)
            ->statePath('data')
            ->model($this->collection); // @phpstan-ignore-line
    }

    public function store(): void
    {
        $this->collection->update($this->form->getState());

        Notification::make()
            ->title(__('shopper::notifications.update', ['item' => __('shopper::pages/collections.single')]))
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.collections.edit')
            ->title(__('shopper::forms.actions.edit_label', ['label' => $this->collection->name]));
    }
}
