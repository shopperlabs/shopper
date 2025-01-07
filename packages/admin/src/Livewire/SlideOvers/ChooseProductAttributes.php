<?php

declare(strict_types=1);

namespace Shopper\Livewire\SlideOvers;

use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\IconSize;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use JaOcero\RadioDeck\Forms\Components\RadioDeck;
use Shopper\Actions\Store\Product\AttachedAttributesToProductAction;
use Shopper\Components;
use Shopper\Core\Enum\FieldType;
use Shopper\Core\Models\Attribute;
use Shopper\Core\Models\AttributeProduct;
use Shopper\Core\Repositories\ProductRepository;
use Shopper\Livewire\Components\SlideOverComponent;

/**
 * @property Form $form
 */
class ChooseProductAttributes extends SlideOverComponent implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public $productId;

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Components\SlideOverWizard::make([
                    Components\Wizard\StepColumn::make(__('shopper::pages/attributes.menu'))
                        ->icon('untitledui-puzzle-piece')
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
                                    fn (RadioDeck $component) => $component->getContainer()
                                        ->getParentComponent()
                                        ->getContainer()
                                        ->getComponent('values')
                                        ->getChildComponentContainer()
                                        ->fill()
                                )
                                ->multiple()
                                ->required(),
                        ]),
                    Components\Wizard\StepColumn::make(__('shopper::pages/attributes.values.slug'))
                        ->icon('untitledui-dotpoints')
                        ->schema([
                            Forms\Components\Grid::make()
                                ->schema(function (Forms\Get $get): array {
                                    $selectSchema = [];
                                    $textSchema = [];

                                    $attributes = Attribute::with('values')
                                        ->select('id', 'name', 'type', 'slug')
                                        ->whereIn('id', $get('attributes'))
                                        ->get();

                                    $selectedAttributes = AttributeProduct::query()
                                        ->where('product_id', $this->productId)
                                        ->whereIn('attribute_id', $get('attributes'))
                                        ->get()
                                        ->mapToGroups(fn (AttributeProduct $attributeProduct) => [
                                            $attributeProduct->attribute_id => $attributeProduct->attribute_value_id,
                                        ]);

                                    foreach ($attributes as $attribute) {
                                        /** @var Attribute $attribute */
                                        if ($attribute->hasMultipleValues() || $attribute->hasSingleValue()) {
                                            $selectSchema[] = Forms\Components\Select::make("values.{$attribute->id}")
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
                                                FieldType::RichText => Forms\Components\RichEditor::make("values.custom_value.{$attribute->id}")
                                                    ->label($attribute->name)
                                                    ->key($attribute->slug)
                                                    ->disabled($selectedAttributes->get($attribute->id) !== null)
                                                    ->columnSpanFull(),
                                                FieldType::DatePicker => Forms\Components\DatePicker::make("values.custom_value.{$attribute->id}")
                                                    ->label($attribute->name)
                                                    ->key($attribute->slug)
                                                    ->disabled($selectedAttributes->get($attribute->id) !== null)
                                                    ->native(false),
                                                default => Forms\Components\TextInput::make("values.custom_value.{$attribute->id}")
                                                    ->key($attribute->slug)
                                                    ->disabled($selectedAttributes->get($attribute->id) !== null)
                                                    ->label($attribute->name),
                                            };

                                            $textSchema[] = $field;
                                        }
                                    }

                                    return array_merge(
                                        $selectSchema,
                                        count($textSchema) > 0 ? [Components\Separator::make()->columnSpanFull()] : [],
                                        $textSchema
                                    );
                                })
                                ->key('values'),
                        ]),
                ])
                    ->submitAction(new HtmlString(Blade::render(<<<'BLADE'
                        <x-shopper::buttons.primary type="submit" wire:loading.attr="disabled">
                            <x-shopper::loader wire:loading wire:target="store" class="text-white" />
                            {{ __('shopper::forms.actions.save') }}
                        </x-shopper::buttons.primary>
                     BLADE)))
                    ->persistStepInQueryString(),
            ])
            ->statePath('data');
    }

    public function store(): void
    {
        $values = data_get($this->form->getState(), 'values');

        app()->call(AttachedAttributesToProductAction::class, [
            'product' => (new ProductRepository)->getById($this->productId),
            'attributes' => Arr::except($values, 'custom_value'),
            'customValues' => Arr::get($values, 'custom_value', []),
        ]);

        Notification::make()
            ->title(__('shopper::pages/products.attributes.session.added_message'))
            ->success()
            ->send();

        $this->redirect(
            route('shopper.products.edit', ['product' => $this->productId, 'tab' => 'attributes']),
            navigate: true
        );
    }

    public static function panelMaxWidth(): string
    {
        return '4xl';
    }

    public function render(): View
    {
        return view('shopper::livewire.slide-overs.choose-product-attributes');
    }
}
