<?php

declare(strict_types=1);

namespace Shopper\Http\Livewire\Components\Products\Attributes;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Shopper\Core\Models\AttributeProduct;

class SingleChoice extends Component
{
    use Actions;

    public Collection $values;

    public int $productId;

    public int $attributeId;

    public ?Model $model = null;

    public ?int $value = null;

    protected $listeners = [
        'refresh' => '$refresh',
    ];

    public function mount(int $productId, int $attributeId, Collection $values): void
    {
        $this->productId = $productId;
        $this->attributeId = $attributeId;
        $this->values = $values;

        $this->model = AttributeProduct::query()
            ->where('product_id', $this->productId)
            ->where('attribute_id', $this->attributeId)
            ->first();
        $this->value = $this->model?->attribute_value_id; // @phpstan-ignore-line
    }

    public function save(): void
    {
        $this->store();
    }

    public function render(): View
    {
        return view('shopper::livewire.components.products.attributes.single-choice');
    }
}
