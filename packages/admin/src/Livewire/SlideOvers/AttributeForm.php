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
use Filament\Notifications\Notification;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Laravelcm\LivewireSlideOvers\SlideOverComponent;
use Shopper\Components\Form\IconPicker;
use Shopper\Components\Form\SlugInput;
use Shopper\Components\Separator;
use Shopper\Contracts\SlideOverForm;
use Shopper\Core\Enum\FieldType;
use Shopper\Core\Models\Attribute;
use Shopper\Traits\HandlesAuthorizationExceptions;
use Shopper\Traits\InteractsWithSlideOverForm;

/**
 * @property-read Schema $form
 */
class AttributeForm extends SlideOverComponent implements HasActions, HasSchemas, SlideOverForm
{
    use HandlesAuthorizationExceptions;
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithSlideOverForm;

    public ?Attribute $attribute = null;

    public string $action = 'store';

    public ?string $title = null;

    public ?string $description = null;

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    public static function panelMaxWidth(): string
    {
        return 'lg';
    }

    public function mount(?int $attributeId = null): void
    {
        abort_unless($this->authorize('attributes.create') || $this->authorize('attributes.edit'), 403);

        $this->attribute = Attribute::query()->find($attributeId);

        $this->title = $this->attribute
            ? $this->attribute->name
            : __('shopper::forms.actions.add_label', ['label' => __('shopper::pages/attributes.single')]);

        $this->form->fill($this->attribute?->toArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('shopper::forms.label.name'))
                    ->required()
                    ->maxLength(75),
                SlugInput::make('slug')
                    ->from('name')
                    ->maxLength(255)
                    ->unique(table: Attribute::class, column: 'slug', ignoreRecord: true),
                Select::make('type')
                    ->label(__('shopper::forms.label.type'))
                    ->options(FieldType::options())
                    ->required()
                    ->native(false),
                IconPicker::make('icon')
                    ->label(__('shopper::forms.label.icon'))
                    ->iconsSearchResults(),
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
            $this->authorize('attributes.edit');

            $this->attribute->update($this->form->getState());
        } else {
            $this->authorize('attributes.create');

            Attribute::query()->create($this->form->getState());
        }

        Notification::make()
            ->title(__('shopper::pages/attributes.notifications.save'))
            ->success()
            ->send();

        $this->closePanel();

        $this->redirect(route('shopper.attributes.index'), navigate: true);
    }
}
