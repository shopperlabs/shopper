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
use Shopper\Core\Repositories\Store\CategoryRepository;
use Shopper\Livewire\Components\SlideOverComponent;

class CategoryForm extends SlideOverComponent implements HasForms
{
    use InteractsWithForms;

    public Model $category;

    public ?array $data = [];

    public function mount(?int $categoryId = null): void
    {
        $this->category = $categoryId
            ? (new CategoryRepository())->getById($categoryId)
            : (new CategoryRepository())->makeModel();

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
                            ->label(__('shopper::layout.forms.label.name'))
                            ->placeholder('Women, Baby Shoes, MacBook...')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $operation, $state, Set $set): void {
                                $set('slug', Str::slug($state));
                            }),
                        Components\Hidden::make('slug'),
                        Components\Select::make('parent_id')
                            ->label(__('shopper::layout.forms.label.parent'))
                            ->relationship('parent', 'name', fn (Builder $query) => $query->where('is_enabled', true))
                            ->getOptionLabelFromRecordUsing(fn (Model $model) => $model->parent ? "{$model->parent->name} / {$model->name}" : $model->name)
                            ->searchable()
                            ->placeholder(__('shopper::pages/categories.empty_parent')),
                        Components\Toggle::make('is_enabled')
                            ->label(__('shopper::layout.forms.label.visibility'))
                            ->onColor('success')
                            ->helperText(__('shopper::words.set_visibility', ['name' => mb_strtolower(__('shopper::words.brand'))])),
                        Components\RichEditor::make('description')
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
                    ]),
                Section::make(__('shopper::words.media'))
                    ->collapsible()
                    ->compact()
                    ->schema([
                        Components\SpatieMediaLibraryFileUpload::make('file')
                            ->label(__('shopper::layout.forms.label.image_preview'))
                            ->collection(config('shopper.core.storage.collection_name'))
                            ->image()
                            ->maxSize(1024),
                    ]),
                Section::make('Seo')
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
        if ($this->category->id) {
            $this->category->update($this->form->getState());
        } else {
            $category = (new CategoryRepository())->create($this->form->getState());
            $this->form->model($category)->saveRelationships();
        }

        Notification::make()
            ->title(__('shopper::notifications.actions.save', ['item' => __('shopper::words.category')]))
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
