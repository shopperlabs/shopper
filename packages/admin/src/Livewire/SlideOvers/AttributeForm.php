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
use Shopper\Components;
use Shopper\Core\Enum\FieldType;
use Shopper\Core\Models\Attribute;
use Shopper\Livewire\Components\SlideOverComponent;

/**
 * @property Forms\Form $form
 */
class AttributeForm extends SlideOverComponent implements HasForms
{
    use InteractsWithForms;

    public ?Attribute $attribute = null;

    public ?array $data = [];

    public function mount(?int $attributeId = null): void
    {
        abort_unless($this->authorize('add_attributes') || $this->authorize('edit_attributes'), 403);

        $this->attribute = Attribute::query()->find($attributeId);

        $this->form->fill($this->attribute?->toArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('shopper::forms.label.name'))
                    ->required()
                    ->live(onBlur: true)
                    ->maxLength(75)
                    ->afterStateUpdated(function (string $operation, $state, Forms\Set $set): void {
                        $set('slug', Str::slug($state));
                    }),

                Forms\Components\TextInput::make('slug')
                    ->label(__('shopper::forms.label.slug'))
                    ->disabled()
                    ->dehydrated()
                    ->required()
                    ->maxLength(255)
                    ->unique(table: Attribute::class, column: 'slug', ignoreRecord: true),

                Forms\Components\Select::make('type')
                    ->label(__('shopper::forms.label.type'))
                    ->options(FieldType::class)
                    ->required()
                    ->native(false),

                Components\Form\IconPicker::make('icon')
                    ->label(__('shopper::forms.label.icon')),

                Forms\Components\Textarea::make('description')
                    ->label(__('shopper::forms.label.description'))
                    ->rows(3),

                Forms\Components\Toggle::make('is_enabled')
                    ->label(__('shopper::forms.actions.enable'))
                    ->onColor('success')
                    ->helperText(__('shopper::pages/attributes.attribute_visibility')),

                Components\Separator::make(),

                Forms\Components\Checkbox::make('is_searchable')
                    ->label(__('shopper::forms.label.is_searchable'))
                    ->helperText(__('shopper::pages/attributes.searchable_description')),

                Forms\Components\Checkbox::make('is_filterable')
                    ->label(__('shopper::forms.label.is_filterable'))
                    ->helperText(__('shopper::pages/attributes.filtrable_description')),
            ])
            ->statePath('data')
            ->model($this->attribute);
    }

    public function store(): void
    {
        if ($this->attribute) {
            $this->attribute->update($this->form->getState());
        } else {
            Attribute::query()->create($this->form->getState());
        }

        Notification::make()
            ->title(__('shopper::pages/attributes.notifications.save'))
            ->success()
            ->send();

        $this->dispatch('attribute-save');

        $this->closePanel();
    }

    public function render(): View
    {
        return view('shopper::livewire.slide-overs.attribute-form');
    }
}
