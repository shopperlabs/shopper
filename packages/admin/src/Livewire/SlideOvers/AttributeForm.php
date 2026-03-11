<?php

declare(strict_types=1);

namespace Shopper\Livewire\SlideOvers;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Shopper\Components\Form\IconPicker;
use Shopper\Components\Separator;
use Shopper\Core\Enum\FieldType;
use Shopper\Core\Models\Attribute;
use Shopper\Livewire\Components\SlideOverComponent;
use Shopper\Traits\HandlesAuthorizationExceptions;

/**
 * @property-read Schema $form
 */
class AttributeForm extends SlideOverComponent implements HasActions, HasForms
{
    use HandlesAuthorizationExceptions;
    use InteractsWithActions;
    use InteractsWithForms;

    public ?Attribute $attribute = null;

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    public function mount(?int $attributeId = null): void
    {
        abort_unless($this->authorize('add_attributes') || $this->authorize('edit_attributes'), 403);

        $this->attribute = Attribute::query()->find($attributeId);

        $this->form->fill($this->attribute?->toArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('shopper::forms.label.name'))
                    ->required()
                    ->live(onBlur: true)
                    ->maxLength(75)
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
                    ->unique(table: Attribute::class, column: 'slug', ignoreRecord: true),
                Select::make('type')
                    ->label(__('shopper::forms.label.type'))
                    ->options(FieldType::options())
                    ->required()
                    ->native(false),
                IconPicker::make('icon')
                    ->label(__('shopper::forms.label.icon')),
                Textarea::make('description')
                    ->label(__('shopper::forms.label.description'))
                    ->hint(__('shopper::words.characters', ['number' => 100]))
                    ->maxLength(100)
                    ->rows(3),
                Toggle::make('is_enabled')
                    ->label(__('shopper::forms.actions.enable'))
                    ->onColor('success')
                    ->helperText(__('shopper::pages/attributes.attribute_visibility')),
                Separator::make(),
                Checkbox::make('is_searchable')
                    ->label(__('shopper::forms.label.is_searchable'))
                    ->helperText(__('shopper::pages/attributes.searchable_description')),
                Checkbox::make('is_filterable')
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

        $this->closePanel();

        $this->redirect(route('shopper.attributes.index'), navigate: true);
    }

    public function render(): View
    {
        return view('shopper::livewire.slide-overs.attribute-form');
    }
}
