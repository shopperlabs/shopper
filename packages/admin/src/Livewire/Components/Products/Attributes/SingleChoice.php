<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Products\Attributes;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Shopper\Core\Models\Attribute;
use Shopper\Core\Models\AttributeProduct;
use Shopper\Core\Models\Product;

class SingleChoice extends Component implements HasActions, HasForms
{
    use Actions;
    use InteractsWithActions;
    use InteractsWithForms;

    public Product $product;

    public Attribute $attribute;

    public ?AttributeProduct $model = null;

    public ?int $value = null;

    public function mount(): void
    {
        $this->model = AttributeProduct::query()
            ->where('product_id', $this->product->id)
            ->where('attribute_id', $this->attribute->id)
            ->first();

        $this->value = $this->model?->attribute_value_id;
    }

    public function render(): View
    {
        return view('shopper::livewire.components.products.attributes.single-choice');
    }
}
