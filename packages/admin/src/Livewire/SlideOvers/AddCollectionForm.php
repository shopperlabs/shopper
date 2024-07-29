<?php

declare(strict_types=1);

namespace Shopper\Livewire\SlideOvers;

use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Shopper\Components\Form\SeoField;
use Shopper\Components\Section;
use Shopper\Core\Enum\CollectionType;
use Shopper\Core\Models\Collection;
use Shopper\Core\Repositories\Store\CollectionRepository;
use Shopper\Livewire\Components\SlideOverComponent;

class AddCollectionForm extends SlideOverComponent implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public function mount(): void
    {
        $this->authorize('add_collections');

        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('shopper::words.general'))
                    ->collapsible()
                    ->compact()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('shopper::forms.label.name'))
                            ->placeholder('Summers Collections, Christmas promotions...')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $operation, $state, Forms\Set $set): void {
                                $set('slug', Str::slug($state));
                            }),

                        Forms\Components\TextInput::make('slug')
                            ->label(__('shopper::forms.label.slug'))
                            ->disabled()
                            ->dehydrated()
                            ->required()
                            ->maxLength(255)
                            ->unique(table: config('shopper.models.collection'), column: 'slug'),

                        Forms\Components\DateTimePicker::make('published_at')
                            ->label(__('shopper::forms.label.availability'))
                            ->native(false)
                            ->default(now())
                            ->helperText(__('shopper::pages/collections.availability_description')),

                        Forms\Components\Radio::make('type')
                            ->label(__('shopper::pages/collections.filter_type'))
                            ->required()
                            ->options(CollectionType::class),

                        Forms\Components\RichEditor::make('description')
                            ->label(__('shopper::forms.label.description'))
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'link',
                                'redo',
                                'strike',
                                'underline',
                                'undo',
                            ]),
                    ]),

                Section::make(__('shopper::words.media'))
                    ->collapsible()
                    ->compact()
                    ->schema([
                        Forms\Components\SpatieMediaLibraryFileUpload::make('file')
                            ->label(__('shopper::forms.label.image_preview'))
                            ->collection(config('shopper.core.storage.thumbnail_collection'))
                            ->image()
                            ->maxSize(1024),
                    ]),

                Section::make(__('shopper::words.seo.slug'))
                    ->collapsed()
                    ->compact()
                    ->schema(SeoField::make()),

                Section::make('Metadata')
                    ->collapsed()
                    ->compact()
                    ->schema([
                        Forms\Components\KeyValue::make('metadata')
                            ->reorderable(),
                    ]),
            ])
            ->statePath('data')
            ->model(config('shopper.models.collection'));
    }

    public function store(): void
    {
        /** @var Collection $collection */
        $collection = (new CollectionRepository)->create($this->form->getState());
        $this->form->model($collection)->saveRelationships();

        Notification::make()
            ->title(__('shopper::notifications.create', ['item' => __('shopper::pages/collections.single')]))
            ->success()
            ->send();

        $this->redirectRoute(
            name: 'shopper.collections.edit',
            parameters: ['collection' => $collection],
            navigate: true
        );
    }

    public function render(): View
    {
        return view('shopper::livewire.slide-overs.add-collection-form');
    }
}
