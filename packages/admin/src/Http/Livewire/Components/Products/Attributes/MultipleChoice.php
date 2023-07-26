<?php

declare(strict_types=1);

namespace Shopper\Http\Livewire\Components\Products\Attributes;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Shopper\Core\Repositories\Ecommerce\ProductRepository;

class MultipleChoice extends Component
{
    public Collection $values;

    public string $type;

    public int $productId;

    public int $attributeId;

    public array $multipleValues = [];

    public function getProductProperty(): Model
    {
        return (new ProductRepository())->getById($this->productId);
    }

    public function save(): void
    {
        $this->validate([
            'multipleValues' => 'required|array',
        ]);

        $this->product->attributes()->attach($this->attributeId, [
            'values' => $this->multipleValues,
        ]);

    }

    public function render(): View
    {
        return view('shopper::livewire.components.products.attributes.multiple-choice');
    }
}
