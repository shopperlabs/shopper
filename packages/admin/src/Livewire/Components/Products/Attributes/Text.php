<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Products\Attributes;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Shopper\Core\Models\AttributeProduct;

final class Text extends Component
{
    use Actions;

    public string $type;

    public int $productId;

    public int $attributeId;

    public ?Model $model = null;

    public ?string $value = null;

    protected $listeners = [
        'refresh' => '$refresh',
        'trix:valueUpdated' => 'onTrixValueUpdate',
    ];

    public function mount(int $productId, string $type, int $attributeId): void
    {
        $this->productId = $productId;
        $this->type = $type;
        $this->attributeId = $attributeId;

        $this->model = AttributeProduct::query()
            ->where('product_id', $this->productId)
            ->where('attribute_id', $this->attributeId)
            ->first();
        $this->value = $this->model?->attribute_custom_value;
    }

    public function onTrixValueUpdate(string $value): void
    {
        $this->value = $value;
    }

    public function save(): void
    {
        $this->store('custom');
    }

    public function render(): View
    {
        return view('shopper::livewire.components.products.attributes.text');
    }
}
