<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Products\Attributes;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Shopper\Core\Enum\FieldType;
use Shopper\Core\Models\Attribute;
use Shopper\Core\Models\AttributeProduct;
use Shopper\Core\Models\Product;

/**
 * @property Forms\Form $form
 */
class Text extends Component implements HasActions, HasForms
{
    use Actions;
    use InteractsWithActions;
    use InteractsWithForms;

    public Product $product;

    public Attribute $attribute;

    public ?AttributeProduct $model = null;

    public ?string $value = null;

    public ?array $data = [];

    public function mount(): void
    {
        $this->model = AttributeProduct::query()
            ->where('product_id', $this->product->id)
            ->where('attribute_id', $this->attribute->id)
            ->whereNull('attribute_value_id')
            ->first();

        $this->value = $this->model?->attribute_custom_value;

        $this->form->fill($this->model?->toArray());
    }

    public function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\RichEditor::make('attribute_custom_value')
                    ->hiddenLabel()
                    ->toolbarButtons([
                        'attachFiles',
                        'bold',
                        'italic',
                        'link',
                        'redo',
                        'strike',
                        'underline',
                        'undo',
                    ])
                    ->required()
                    ->visible($this->attribute->type === FieldType::RichText),

                Forms\Components\DatePicker::make('attribute_custom_value')
                    ->hiddenLabel()
                    ->native(false)
                    ->required()
                    ->visible($this->attribute->type === FieldType::DatePicker),

                Forms\Components\TextInput::make('attribute_custom_value')
                    ->hiddenLabel()
                    ->required()
                    ->visible($this->attribute->type === FieldType::Text),

                Forms\Components\TextInput::make('attribute_custom_value')
                    ->hiddenLabel()
                    ->numeric()
                    ->required()
                    ->visible($this->attribute->type === FieldType::Number),
            ])
            ->statePath('data')
            ->model($this->model);
    }

    public function store(): void
    {
        $this->saveData($this->form->getState());
    }

    public function render(): View
    {
        return view('shopper::livewire.components.products.attributes.text');
    }
}
