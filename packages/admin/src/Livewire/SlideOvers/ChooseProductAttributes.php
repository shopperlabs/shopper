<?php

declare(strict_types=1);

namespace Shopper\Livewire\SlideOvers;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\IconSize;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use JaOcero\RadioDeck\Forms\Components\RadioDeck;
use Mckenziearts\Icons\Untitledui\Enums\Untitledui;
use Shopper\Actions\Store\Product\AttachedAttributesToProductAction;
use Shopper\Components\Separator;
use Shopper\Components\SlideOverWizard;
use Shopper\Components\Wizard\StepColumn;
use Shopper\Core\Enum\FieldType;
use Shopper\Core\Models\Attribute;
use Shopper\Core\Models\AttributeProduct;
use Shopper\Core\Models\Contracts\Product;
use Shopper\Livewire\Components\SlideOverComponent;

/**
 * @property-read Schema $form
 */
class ChooseProductAttributes extends SlideOverComponent implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public Product $product;

    public static function panelMaxWidth(): string
    {
        return '4xl';
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                SlideOverWizard::make([
                    StepColumn::make(__('shopper::pages/attributes.menu'))
                        ->icon(Untitledui::PuzzlePiece)
                        ->schema([
                            RadioDeck::make('attributes')
                                ->options(
                                    Attribute::query()
                                        ->scopes('enabled')
                                        ->select('id', 'name')
                                        ->pluck('name', 'id')
                                )
                                ->descriptions(
                                    Attribute::query()
                                        ->scopes('enabled')
                                        ->select('id', 'description')
                                        ->pluck('description', 'id')
                                        ->toArray()
                                )
                                ->icons(
                                    Attribute::query()
                                        ->scopes('enabled')
                                        ->select('id', 'icon')
                                        ->pluck('icon', 'id')
                                        ->toArray()
                                )
                                ->alignment(Alignment::Start)
                                ->iconSize(IconSize::Small)
                                ->color('primary')
                                ->columns(3)
                                ->live()
                                ->afterStateUpdated(
                                    fn (RadioDeck $component): Schema => $component->getContainer()
                                        ->getParentComponent()
                                        ->getContainer()
                                        ->getComponent('values')
                                        ->getChildSchema()
                                        ->fill()
                                )
                                ->multiple()
                                ->required(),
                        ]),
                    StepColumn::make(__('shopper::pages/attributes.values.slug'))
                        ->icon(Untitledui::Dotpoints)
                        ->schema([
                            Grid::make()
                                ->schema(function (Get $get): array {
                                    $selectSchema = [];
                                    $textSchema = [];

                                    $attributes = Attribute::with('values')
                                        ->select('id', 'name', 'type', 'slug')
                                        ->whereIn('id', $get('attributes'))
                                        ->get();

                                    $selectedAttributes = AttributeProduct::query()
                                        ->where('product_id', $this->product->id)
                                        ->whereIn('attribute_id', $get('attributes'))
                                        ->get()
                                        ->mapToGroups(fn (AttributeProduct $attributeProduct): array => [
                                            $attributeProduct->attribute_id => $attributeProduct->attribute_value_id,
                                        ]);

                                    foreach ($attributes as $attribute) {
                                        /** @var Attribute $attribute */
                                        if ($attribute->hasMultipleValues() || $attribute->hasSingleValue()) {
                                            $selectSchema[] = Select::make("values.{$attribute->id}")
                                                ->key($attribute->slug)
                                                ->label($attribute->name)
                                                ->required()
                                                ->options($attribute->values->pluck('value', 'id'))
                                                ->disableOptionWhen(
                                                    fn (string $value): bool => in_array(
                                                        $value,
                                                        $selectedAttributes->get($attribute->id)?->toArray() ?? []
                                                    )
                                                )
                                                ->multiple($attribute->hasMultipleValues())
                                                ->preload()
                                                ->optionsLimit(10)
                                                ->native(false);
                                        }

                                        if ($attribute->hasTextValue()) {
                                            $field = match ($attribute->type) {
                                                FieldType::RichText => RichEditor::make("values.custom_value.{$attribute->id}")
                                                    ->label($attribute->name)
                                                    ->key($attribute->slug)
                                                    ->disabled($selectedAttributes->get($attribute->id) !== null)
                                                    ->columnSpanFull(),
                                                FieldType::DatePicker => DatePicker::make("values.custom_value.{$attribute->id}")
                                                    ->label($attribute->name)
                                                    ->key($attribute->slug)
                                                    ->disabled($selectedAttributes->get($attribute->id) !== null)
                                                    ->native(false),
                                                default => TextInput::make("values.custom_value.{$attribute->id}")
                                                    ->key($attribute->slug)
                                                    ->disabled($selectedAttributes->get($attribute->id) !== null)
                                                    ->label($attribute->name),
                                            };

                                            $textSchema[] = $field;
                                        }
                                    }

                                    return array_merge(
                                        $selectSchema,
                                        count($textSchema) > 0 ? [Separator::make()->columnSpanFull()] : [],
                                        $textSchema
                                    );
                                })
                                ->key('values'),
                        ]),
                ])
                    ->submitAction(new HtmlString(Blade::render(<<<'BLADE'
                        <x-filament::button type="submit" wire:loading.attr="disabled">
                            <x-shopper::loader wire:loading wire:target="store" class="text-white" />
                            {{ __('shopper::forms.actions.save') }}
                        </x-filament::button>
                     BLADE)))
                    ->persistStepInQueryString(),
            ])
            ->statePath('data');
    }

    public function store(): void
    {
        $values = data_get($this->form->getState(), 'values');

        app()->call(AttachedAttributesToProductAction::class, [
            'product' => $this->product,
            'attributes' => Arr::except($values, 'custom_value'),
            'customValues' => Arr::get($values, 'custom_value', []),
        ]);

        Notification::make()
            ->title(__('shopper::pages/products.attributes.session.added_message'))
            ->success()
            ->send();

        $this->redirect(
            route('shopper.products.edit', ['product' => $this->product, 'tab' => 'attributes']),
            navigate: true
        );
    }

    public function render(): View
    {
        return view('shopper::livewire.slide-overs.choose-product-attributes');
    }
}
