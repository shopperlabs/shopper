<?php

declare(strict_types=1);

namespace Shopper\Livewire\SlideOvers;

use Filament\Forms\Components;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Shopper\Components\Form\SeoField;
use Shopper\Components\Section;
use Shopper\Core\Models\Category;
use Shopper\Core\Repositories\Store\CategoryRepository;
use Shopper\Livewire\Components\SlideOverComponent;

/**
 * @property Form $form
 */
class CategoryForm extends SlideOverComponent implements HasForms
{
    use InteractsWithForms;

    /**
     * @var Category|Model
     */
    public $category;

    public ?array $data = [];

    public function mount(?int $categoryId = null): void
    {
        $this->category = $categoryId
            ? (new CategoryRepository)->getById($categoryId)
            : (new CategoryRepository)->makeModel();

        $this->form->fill($this->category->toArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('shopper::words.general'))
                    ->collapsible()
                    ->compact()
                    ->schema([
                        Components\TextInput::make('name')
                            ->label(__('shopper::forms.label.name'))
                            ->placeholder('Women, Baby Shoes, MacBook...')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $operation, $state, Set $set): void {
                                $set('slug', Str::slug($state));
                            }),
                        Components\Hidden::make('slug'),
                        Components\Select::make('parent_id')
                            ->label(__('shopper::forms.label.parent'))
                            ->relationship('parent', 'name', fn (Builder $query) => $query->where('is_enabled', true))
                            ->getOptionLabelFromRecordUsing(
                                fn ($model) => $model->parent
                                    ? "{$model->parent->name} / {$model->name}"
                                    : $model->name
                            )
                            ->searchable()
                            ->placeholder(__('shopper::pages/categories.empty_parent')),
                        Components\Toggle::make('is_enabled')
                            ->label(__('shopper::forms.label.visibility'))
                            ->helperText(__('shopper::words.set_visibility', ['name' => __('shopper::pages/categories.single')])),
                        Components\RichEditor::make('description')
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
                        Components\SpatieMediaLibraryFileUpload::make('file')
                            ->label(__('shopper::forms.label.image_preview'))
                            ->collection(config('shopper.core.storage.thumbnail_collection'))
                            ->image()
                            ->maxSize(1024),
                    ]),
                Section::make(__('shopper::words.seo.slug'))
                    ->collapsible()
                    ->compact()
                    ->schema(SeoField::make()),
                Section::make('Metadata')
                    ->collapsible()
                    ->compact()
                    ->schema([
                        Components\KeyValue::make('metadata')
                            ->reorderable(),
                    ]),
            ])
            ->statePath('data')
            ->model($this->category);
    }

    public function save(): void
    {
        // @phpstan-ignore-next-line
        if ($this->category->id) {
            $this->category->update($this->form->getState());
        } else {
            $category = (new CategoryRepository)->create($this->form->getState());
            $this->form->model($category)->saveRelationships();
        }

        Notification::make()
            ->title(__('shopper::notifications.save', ['item' => __('shopper::pages/categories.single')]))
            ->success()
            ->send();

        $this->dispatch('category-save');

        $this->closePanel();

        $this->form->fill();
    }

    public function render(): View
    {
        return view('shopper::livewire.slide-overs.category-form');
    }
}
