<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Products\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Shopper\Core\Models\Attribute;
use Shopper\Core\Models\AttributeProduct;

class Attributes extends Component
{
    public Model $product;

    public Collection $attributes;

    public int $productId;

    public array $multipleValues = [];

    protected $listeners = [
        'onProductAttributeUpdated' => '$refresh',
    ];

    public function mount($product): void
    {
        $this->product = $product;
        $this->productId = $product->id;

        $this->attributes = $this->getAttributes();
    }

    public function getAttributes(): Collection
    {
        return Attribute::with('values')
            ->select(['id', 'name', 'type', 'is_enabled', 'icon', 'description'])
            ->get();
    }

    public function getAttributesValues(): \Illuminate\Support\Collection
    {
        return AttributeProduct::query()
            ->where('product_id', $this->product->id) // @phpstan-ignore-line
            ->get()
            ->keys();
    }

    public function getCurrentAttributes(): \Illuminate\Support\Collection
    {
        return AttributeProduct::query()
            ->where('product_id', $this->product->id) // @phpstan-ignore-line
            ->distinct()
            ->get()
            ->groupBy('attribute_id')
            ->keys();
    }

    public function render(): View
    {
        return view('shopper::livewire.products.forms.form-attributes', [
            'currentAttributes' => $this->getCurrentAttributes(),
            'attributesValues' => $this->getAttributesValues(),
        ]);
    }
}
