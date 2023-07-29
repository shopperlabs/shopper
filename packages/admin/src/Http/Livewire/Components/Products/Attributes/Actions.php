<?php

namespace Shopper\Http\Livewire\Components\Products\Attributes;

use Filament\Notifications\Notification;
use Shopper\Core\Models\AttributeProduct;

trait Actions
{
    public function store(string $column = 'value'): void
    {
        $this->validate(['value' => 'required']);

        $this->model = AttributeProduct::query()->updateOrCreate([
            'product_id' => $this->productId,
            'attribute_id' => $this->attributeId,
        ], [
            $column === 'value' ? 'attribute_value_id' : 'attribute_custom_value' => $this->value,
        ]);

        Notification::make()
            ->title(__('shopper::pages/products.attributes.session.added'))
            ->body(__('shopper::pages/products.attributes.session.added_message'))
            ->success()
            ->send();

        $this->emitUp('onProductAttributeUpdated');
        $this->emit('refresh');
    }

    public function remove(int $id): void
    {
        AttributeProduct::query()->find($id)->delete();

        Notification::make()
            ->title(__('shopper::pages/products.attributes.session.delete'))
            ->body(__('shopper::pages/products.attributes.session.delete_message'))
            ->success()
            ->send();

        $this->model = null;
        $this->value = null;

        $this->emitUp('onProductAttributeUpdated');
        $this->emit('refresh');
    }
}
