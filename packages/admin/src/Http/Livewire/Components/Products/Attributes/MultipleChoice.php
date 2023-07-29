<?php

declare(strict_types=1);

namespace Shopper\Http\Livewire\Components\Products\Attributes;

use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Shopper\Core\Models\AttributeProduct;
use Shopper\Core\Repositories\Ecommerce\ProductRepository;

final class MultipleChoice extends Component
{
    public Collection $values;

    public string $type;

    public int $productId;

    public int $attributeId;

    public array $selected = [];

    protected $listeners = [
        'refresh' => '$refresh',
    ];

    public function mount(int $productId, string $type, int $attributeId, Collection $values): void
    {
        $this->type = $type;
        $this->productId = $productId;
        $this->attributeId = $attributeId;
        $this->values = $values;

        $this->selected = $this->currentValues->toArray();
    }

    public function getProductProperty(): Model
    {
        return (new ProductRepository())->getById($this->productId);
    }

    public function getCurrentValuesProperty(): \Illuminate\Support\Collection
    {
        return $this->product->attributes() // @phpstan-ignore-line
            ->where('attribute_id', $this->attributeId)
            ->get()
            ->pluck('attribute_value_id');
    }

    public function save(): void
    {
        $this->validate(['selected' => 'array']);

        $toDelete = array_diff($this->currentValues->toArray(), $this->selected);

        if (count($toDelete) > 0) {
            AttributeProduct::query()
                ->where('product_id', $this->product->id) // @phpstan-ignore-line
                ->whereIn('attribute_value_id', $toDelete)
                ->delete();
        }

        foreach ($this->selected as $value) {
            if (! $this->currentValues->contains($value)) {
                AttributeProduct::query()->create([
                    'product_id' => $this->product->id, // @phpstan-ignore-line
                    'attribute_id' => $this->attributeId,
                    'attribute_value_id' => $value,
                ]);
            }
        }

        Notification::make()
            ->title(__('shopper::pages/products.attributes.session.added'))
            ->body(__('shopper::pages/products.attributes.session.added_message'))
            ->success()
            ->send();

        $this->emitUp('onProductAttributeUpdated');
        $this->emit('refresh');
    }

    public function render(): View
    {
        return view('shopper::livewire.components.products.attributes.multiple-choice');
    }
}
