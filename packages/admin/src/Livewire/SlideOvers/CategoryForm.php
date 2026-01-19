<?php

declare(strict_types=1);

namespace Shopper\Livewire\SlideOvers;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Shopper\Components\Form\SeoField;
use Shopper\Components\Section;
use Shopper\Core\Models\Contracts\Category;
use Shopper\Livewire\Components\SlideOverComponent;

/**
 * @property-read Schema $form
 */
class CategoryForm extends SlideOverComponent implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    public Category $category;

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public function mount(?Category $category = null): void
    {
        $this->category = $category ?? resolve(Category::class)::query()->newModelInstance();

        $this->form->fill($this->category->toArray());
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
                            ->placeholder('Women, Baby Shoes, MacBook...')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $operation, ?string $state, Set $set): void {
                                if ($state) {
                                    $set('slug', Str::slug($state));
                                }
                            }),
                        Hidden::make('slug'),
                        Select::make('parent_id')
                            ->label(__('shopper::forms.label.parent'))
                            ->relationship(
                                name: 'parent',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn (Builder $query) => $query->where('is_enabled', true)
                            )
                            ->getOptionLabelFromRecordUsing(fn (Category $record) => $record->load('parent')->getLabelOptionName())
                            ->preload()
                            ->searchable()
                            ->optionsLimit(20)
                            ->placeholder(__('shopper::pages/categories.empty_parent')),
                        Toggle::make('is_enabled')
                            ->label(__('shopper::forms.label.visibility'))
                            ->helperText(__('shopper::words.set_visibility', ['name' => __('shopper::pages/categories.single')])),
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
                Section::make(__('Metadata'))
                    ->collapsible()
                    ->compact()
                    ->schema([
                        KeyValue::make('metadata')->reorderable(),
                    ]),
            ])
            ->statePath('data')
            ->model($this->category); // @phpstan-ignore-line
    }

    public function save(): void
    {
        if ($this->category->id) {
            $this->authorize('edit_categories', $this->category);

            $this->category->update($this->form->getState());
        } else {
            $this->authorize('add_categories');

            $category = resolve(Category::class)::query()->create($this->form->getState());
            $this->form->model($category)->saveRelationships();
        }

        Notification::make()
            ->title(__('shopper::notifications.save', ['item' => __('shopper::pages/categories.single')]))
            ->success()
            ->send();

        $this->redirectRoute(
            name: 'shopper.categories.index',
            navigate: true,
        );
    }

    public function render(): View
    {
        return view('shopper::livewire.slide-overs.category-form');
    }
}
