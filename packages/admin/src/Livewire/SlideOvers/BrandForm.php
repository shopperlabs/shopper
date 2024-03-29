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
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Shopper\Components\Section;
use Shopper\Core\Repositories\Store\BrandRepository;
use Shopper\Livewire\Components\SlideOverComponent;

class BrandForm extends SlideOverComponent implements HasForms
{
    use InteractsWithForms;

    public ?Model $brand = null;

    public ?array $data = [];

    public function mount(?int $brandId = null): void
    {
        $this->brand = $brandId
            ? (new BrandRepository())->getById($brandId)
            : (new BrandRepository())->makeModel();

        $this->form->fill($this->brand->toArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('General')
                    ->collapsible()
                    ->compact()
                    ->schema([
                        Components\TextInput::make('name')
                            ->label(__('shopper::layout.forms.label.name'))
                            ->placeholder('Apple, Nike, Samsung...')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $operation, $state, Set $set): void {
                                $set('slug', Str::slug($state));
                            }),
                        Components\Hidden::make('slug'),
                        Components\TextInput::make('website')
                            ->label(__('shopper::layout.forms.label.website'))
                            ->placeholder('https://example.com')
                            ->url(),
                        Components\Toggle::make('is_enabled')
                            ->label(__('shopper::layout.forms.label.visibility'))
                            ->helperText(__('shopper::words.set_visibility', ['name' => strtolower(__('shopper::words.brand'))])),
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
                            ])
                    ]),
                Section::make('Media')
                    ->collapsible()
                    ->compact()
                    ->schema([
                        Components\SpatieMediaLibraryFileUpload::make('file')
                            ->label(__('shopper::layout.forms.label.image_preview'))
                            ->collection(config('shopper.core.storage.collection_name'))
                            ->image()
                            ->maxSize(1024)
                    ]),
                Section::make('Seo')
                    ->collapsible()
                    ->compact()
                    ->schema([
                        Components\TextInput::make('seo_title')
                            ->label(__('shopper::layout.forms.label.title')),
                        Components\Textarea::make('seo_description')
                            ->label(__('shopper::layout.forms.label.description'))
                            ->hint(__('shopper::components.seo.characters'))
                            ->maxLength(160),
                    ]),
                Section::make('Metadata')
                    ->collapsible()
                    ->compact()
                    ->schema([
                        Components\KeyValue::make('metadata')
                            ->reorderable(),
                    ]),
            ])
            ->statePath('data')
            ->model($this->brand);
    }

    public function save(): void
    {
        if ($this->brand->id) {
            $this->brand->update($this->form->getState());
        } else {
            $brand = (new BrandRepository())->create($this->form->getState());
            $this->form->model($brand)->saveRelationships();
        }

        Notification::make()
            ->title(__('shopper::notifications.actions.save', ['item' => __('shopper::words.brand')]))
            ->success()
            ->send();

        $this->dispatch('brand-save');

        $this->closePanel();

        $this->form->fill();
    }

    public function render(): View
    {
        return view('shopper::livewire.slide-overs.brand-form');
    }
}
