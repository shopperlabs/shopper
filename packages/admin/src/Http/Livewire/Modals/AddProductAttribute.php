<?php

declare(strict_types=1);

namespace Shopper\Http\Livewire\Modals;

use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use LivewireUI\Modal\ModalComponent;
use Shopper\Core\Models\Attribute;
use Shopper\Core\Models\ProductAttribute;
use Shopper\Core\Models\ProductAttributeValue;
use Shopper\Core\Repositories\Ecommerce\ProductRepository;
use Shopper\Core\Traits\Attributes\WithAttributes;

class AddProductAttribute extends ModalComponent
{
    use WithAttributes;

    public $product;

    public string $type = 'text';

    public ?int $attribute_id = null;

    public array $multipleValues = [];

    public $attributes;

    public $values;

    public $productAttributes;

    public ?string $value = null;

    protected $listeners = [
        'trix:valueUpdated' => 'onTrixValueUpdate',
    ];

    public function onTrixValueUpdate(string $value): void
    {
        $this->value = $value;
    }

    public function mount(int $productId): void
    {
        $this->product = (new ProductRepository())->getById($productId);
        $this->productAttributes = $this->getProductAttributes();
        $this->attributes = $this->getAttributes();
    }

    public function save(): void
    {
        if ('checkbox' === $this->type || 'colorpicker' === $this->type) {
            $this->validate(['multipleValues' => 'required|array']);
        } else {
            $this->validate(['value' => 'required', 'attribute_id' => 'required|int']);
        }

        $productAttribute = ProductAttribute::query()->create([
            'product_id' => $this->product->id,
            'attribute_id' => $this->attribute_id,
        ]);

        if ('checkbox' === $this->type || 'colorpicker' === $this->type) {
            foreach ($this->multipleValues as $checkboxValue) {
                ProductAttributeValue::query()->create([
                    'attribute_value_id' => $checkboxValue,
                    'product_attribute_id' => $productAttribute->id,
                ]);
            }
        } else {
            ProductAttributeValue::query()->create([
                'attribute_value_id' => in_array($this->type, Attribute::fieldsWithStringValues())
                    ? null
                    : $this->value,
                'product_attribute_id' => $productAttribute->id,
                'product_custom_value' => in_array($this->type, Attribute::fieldsWithStringValues())
                    ? $this->value
                    : null,
            ]);
        }

        Notification::make()
            ->title(__('shopper::pages/products.attributes.session.added'))
            ->body(__('shopper::pages/products.attributes.session.added_message'))
            ->success()
            ->send();

        $this->emit('onProductAttributeAdded');

        $this->closeModal();
    }

    public function updatedAttributeId(string $value): void
    {
        if ('0' === $value) {
            return;
        }

        $attribute = Attribute::query()->with('values')->find($value);
        $this->type = $attribute->type;
        $this->value = '';

        if ($attribute->values->isNotEmpty()) {
            $this->values = $attribute->values;
        }
    }

    public static function modalMaxWidth(): string
    {
        return 'xl';
    }

    public function render(): View
    {
        return view('shopper::livewire.modals.add-product-attribute');
    }
}
