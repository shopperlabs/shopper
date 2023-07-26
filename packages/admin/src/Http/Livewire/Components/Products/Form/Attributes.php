<?php

declare(strict_types=1);

namespace Shopper\Http\Livewire\Components\Products\Form;

use Exception;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Shopper\Core\Models\ProductAttribute;
use Shopper\Core\Models\ProductAttributeValue;
use Shopper\Core\Traits\Attributes\WithAttributes;

class Attributes extends Component
{
    use WithAttributes;

    public Model $product;

    public int $productId;

    public $attributes;

    public $productAttributes;

    public array $multipleValues = [];

    protected $listeners = ['onProductAttributeAdded'];

    public function mount($product): void
    {
        $this->product = $product;
        $this->productId = $product->id;

        $this->onProductAttributeAdded();
    }

    public function onProductAttributeAdded(): void
    {
        $this->productAttributes = $this->getProductAttributes();
        $this->attributes = $this->getAttributes();
    }

    public function removeProductAttributeValue(int $id): void
    {
        ProductAttributeValue::query()->find($id)->delete();

        Notification::make()
            ->title(__('shopper::pages/products.attributes.session.delete_value'))
            ->body(__('shopper::pages/products.attributes.session.delete_value_message'))
            ->success()
            ->send();

        $this->emitSelf('onProductAttributeAdded');
    }

    /**
     * Remove Attribute to product.
     *
     * @throws Exception
     */
    public function removeProductAttribute(int $id): void
    {
        ProductAttribute::query()->find($id)->delete();

        $this->productAttributes = $this->getProductAttributes();
        $this->attributes = $this->getAttributes();

        Notification::make()
            ->title(__('shopper::pages/products.attributes.session.delete'))
            ->body(__('shopper::pages/products.attributes.session.delete_message'))
            ->success()
            ->send();
    }

    public function render(): View
    {
        return view('shopper::livewire.products.forms.form-attributes');
    }
}
