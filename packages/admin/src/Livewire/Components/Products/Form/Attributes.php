<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Products\Form;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use Livewire\Component;
use Shopper\Core\Models\Attribute;
use Shopper\Core\Models\AttributeProduct;
use Shopper\Core\Models\Product;

class Attributes extends Component
{
    public Product $product;

    public function groupProductAttributes(): Collection
    {
        $attributes = Attribute::with('values')
            ->select(['id', 'name', 'type', 'is_enabled', 'icon', 'description'])
            ->where('is_enabled', true)
            ->get();

        return $attributes->map(function (Attribute $attribute) {
            $attribute->group = $attribute->hasMultipleValues() || $attribute->hasSingleValue() ? 'choice' : 'text';

            return $attribute;
        })->groupBy('group');
    }

    #[On('attribute-save')]
    public function render(): View
    {
        return view('shopper::livewire.components.products.forms.attributes', [
            'currentAttributes' => AttributeProduct::query()
                ->where('product_id', $this->product->id)
                ->distinct()
                ->get()
                ->groupBy('attribute_id')
                ->keys(),
            'productAttributes' => $this->groupProductAttributes(),
        ]);
    }
}
