<?php

declare(strict_types=1);

namespace Shopper\Livewire\SlideOvers;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Shopper\Components\Form\SeoField;
use Shopper\Components\Section;
use Shopper\Core\Enum\CollectionType;
use Shopper\Core\Models\Contracts\Collection;
use Shopper\Livewire\Components\SlideOverComponent;
use Shopper\Traits\HandlesAuthorizationExceptions;

/**
 * @property-read Schema $form
 */
class AddCollectionForm extends SlideOverComponent implements HasActions, HasSchemas
{
    use HandlesAuthorizationExceptions;
    use InteractsWithActions;
    use InteractsWithSchemas;

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public function mount(): void
    {
        $this->authorize('add_collections');

        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('shopper::words.general'))
                    ->collapsible()
                    ->compact()
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
                        Hidden::make('slug')
                            ->label(__('shopper::forms.label.slug')),
                        DateTimePicker::make('published_at')
                            ->label(__('shopper::forms.label.availability'))
                            ->native(false)
                            ->required()
                            ->default(now())
                            ->minDate(now()->subHour())
                            ->helperText(__('shopper::pages/collections.availability_description')),
                        Radio::make('type')
                            ->label(__('shopper::pages/collections.filter_type'))
                            ->required()
                            ->options(CollectionType::options()),
                        Select::make('zones')
                            ->label(__('shopper::pages/settings/zones.title'))
                            ->relationship('zones', 'name')
                            ->multiple()
                            ->native(false)
                            ->searchable()
                            ->preload()
                            ->helperText(__('shopper::pages/collections.zone_description')),
                        RichEditor::make('description')
                            ->label(__('shopper::forms.label.description'))
                            ->toolbarButtons([
                                ['bold', 'italic', 'link', 'strike', 'underline'],
                                ['bulletList', 'orderedList', 'table', 'attachFiles'],
                                ['undo', 'redo'],
                            ]),
                    ]),
                Section::make(__('shopper::words.media'))
                    ->collapsible()
                    ->compact()
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('file')
                            ->label(__('shopper::forms.label.image_preview'))
                            ->collection(config('shopper.media.storage.thumbnail_collection'))
                            ->image()
                            ->maxSize(config('shopper.media.max_size.thumbnail')),
                    ]),
                Section::make(__('shopper::words.seo.slug'))
                    ->collapsible()
                    ->compact()
                    ->schema(SeoField::make()),
                Section::make('Metadata')
                    ->collapsible()
                    ->compact()
                    ->schema([
                        KeyValue::make('metadata')
                            ->hiddenLabel()
                            ->reorderable(),
                    ]),
            ])
            ->statePath('data')
            ->model(config('shopper.models.collection'));
    }

    public function store(): void
    {
        /** @var Model&Collection $collection */
        $collection = resolve(Collection::class)::query()->create($this->form->getState());
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
