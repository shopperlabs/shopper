<?php

declare(strict_types=1);

namespace Shopper\Livewire\SlideOvers;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Shopper\Components\Form\SeoField;
use Shopper\Components\Section;
use Shopper\Core\Models\Contracts\Brand;
use Shopper\Livewire\Components\SlideOverComponent;

/**
 * @property-read Schema $form
 */
class BrandForm extends SlideOverComponent implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    public Brand $brand;

    /** @var array<array-key, mixed>|null */
    public ?array $data = [];

    public function mount(?Brand $brand = null): void
    {
        $this->brand = $brand ?? resolve(Brand::class)::query()->newModelInstance();

        $this->form->fill($this->brand->toArray());
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
                            ->placeholder('Apple, Nike, Samsung...')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $operation, $state, Set $set): void {
                                $set('slug', Str::slug($state));
                            }),
                        Hidden::make('slug'),
                        TextInput::make('website')
                            ->label(__('shopper::forms.label.website'))
                            ->placeholder('https://example.com')
                            ->url(),
                        Toggle::make('is_enabled')
                            ->label(__('shopper::forms.label.visibility'))
                            ->helperText(__('shopper::words.set_visibility', ['name' => __('shopper::pages/brands.single')])),
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
                        KeyValue::make('metadata')
                            ->hiddenLabel()
                            ->reorderable(),
                    ]),
            ])
            ->statePath('data')
            ->model($this->brand); // @phpstan-ignore-line
    }

    public function save(): void
    {
        if ($this->brand->id) {
            $this->authorize('edit_brands', $this->brand);

            $this->brand->update($this->form->getState());
        } else {
            $this->authorize('add_brands');

            $brand = resolve(Brand::class)::query()->create($this->form->getState());
            $this->form->model($brand)->saveRelationships();
        }

        Notification::make()
            ->title(__('shopper::notifications.save', ['item' => __('shopper::pages/brands.single')]))
            ->success()
            ->send();

        $this->redirectRoute(
            name: 'shopper.brands.index',
            navigate: true,
        );
    }

    public function render(): View
    {
        return view('shopper::livewire.slide-overs.brand-form');
    }
}
