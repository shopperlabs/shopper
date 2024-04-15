<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Collection;

use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Shopper\Components\Form\SeoField;
use Shopper\Core\Enum\CollectionType;
use Shopper\Core\Models\Collection;
use Shopper\Feature;
use Shopper\Livewire\Components\Collection\CollectionProducts;
use Shopper\Livewire\Pages\AbstractPageComponent;

class Edit extends AbstractPageComponent implements HasForms
{
    use InteractsWithForms;

    public Collection $collection;

    public ?array $data = [];

    public function mount(): void
    {
        $this->authorize('edit_collections');

        $this->form->fill($this->collection->toArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label(__('shopper::layout.forms.label.name'))
                                    ->placeholder('Summers Collections, Christmas promotions...')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (string $operation, $state, Forms\Set $set): void {
                                        $set('slug', Str::slug($state));
                                    }),

                                Forms\Components\TextInput::make('slug')
                                    ->label(__('shopper::layout.forms.label.slug'))
                                    ->disabled()
                                    ->dehydrated()
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(config('shopper.models.collection'), 'slug', ignoreRecord: true),
                            ]),

                        Forms\Components\Radio::make('type')
                            ->label(__('shopper::pages/collections.filter_type'))
                            ->inline()
                            ->inlineLabel(false)
                            ->required()
                            ->options([
                                CollectionType::MANUAL->value => __('shopper::pages/collections.manual'),
                                CollectionType::AUTO->value => __('shopper::pages/collections.automatic'),
                            ])
                            ->descriptions([
                                CollectionType::MANUAL->value => __('shopper::pages/collections.manual_description'),
                                CollectionType::AUTO->value => __('shopper::pages/collections.automatic_description'),
                            ]),

                        Forms\Components\RichEditor::make('description')
                            ->label(__('shopper::layout.forms.label.description'))
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'link',
                                'redo',
                                'strike',
                                'underline',
                                'undo',
                            ]),

                        Forms\Components\Livewire::make(CollectionProducts::class, ['collection' => $this->collection])
                            ->visible(Feature::enabled('product')),
                    ])
                    ->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\SpatieMediaLibraryFileUpload::make('file')
                            ->label(__('shopper::layout.forms.label.image_preview'))
                            ->collection(config('shopper.core.storage.thumbnail_collection'))
                            ->image()
                            ->maxSize(1024),

                        Forms\Components\DateTimePicker::make('published_at')
                            ->label(__('shopper::layout.forms.label.availability'))
                            ->native(false)
                            ->default(now())
                            ->helperText(__('shopper::pages/collections.availability_description')),

                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Placeholder::make('seo')
                                    ->label(__('shopper::words.seo.title'))
                                    ->content(new HtmlString(Blade::render(<<<'BLADE'
                                        <p class="max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                                            {{ __('shopper::words.seo.description', ['name' => mb_strtolower(__('shopper::words.collection'))]) }}
                                        </p>
                                    BLADE))),

                                ...SeoField::make(),
                            ]),

                        Forms\Components\KeyValue::make('metadata')
                            ->reorderable(),
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3)
            ->statePath('data')
            ->model($this->collection);
    }

    public function store(): void
    {
        $this->collection->update($this->form->getState());

        Notification::make()
            ->title(__('shopper::notifications.actions.update', ['item' => __('shopper::words.collection')]))
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('shopper::livewire.pages.collections.edit')
            ->title(__('shopper::words.actions_label.edit', ['name' => $this->collection->name]));
    }
}
