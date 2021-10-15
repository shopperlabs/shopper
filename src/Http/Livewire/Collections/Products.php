<?php

namespace Shopper\Framework\Http\Livewire\Collections;

use Livewire\Component;

class Products extends Component
{
    public $collection;
    public string $sortBy = 'name';
    public string $sortValue = 'name';
    public string $direction = 'asc';

    protected $listeners = [
        'onProductsAddInCollection' => 'render',
    ];

    public function mount($collection)
    {
        $this->collection = $collection;
    }

    public function getProductsIdsProperty()
    {
        return $this->collection->products->modelKeys();
    }

    public function removeProduct(int $id): void
    {
        $this->collection->products()->detach([$id]);

        $this->emitSelf('onProductsAddInCollection');

        $this->notify([
            'title' => __('Product removed'),
            'message' => __('The product have been correctly remove to this collection.'),
        ]);
    }

    public function updatedSortBy(string $sortBy): void
    {
        switch ($sortBy) {
            case 'alpha_asc':
                $this->sortValue = 'name';
                $this->direction = 'asc';

                break;
            case 'alpha_desc':
                $this->sortValue = 'name';
                $this->direction = 'desc';

                break;
            case 'price_asc':
                $this->sortValue = 'price_amount';
                $this->direction = 'asc';

                break;
            case 'price_desc':
                $this->sortValue = 'price_amount';
                $this->direction = 'desc';

                break;
            case 'created_asc':
                $this->sortValue = 'created_at';
                $this->direction = 'asc';

                break;
            case 'created_desc':
                $this->sortValue = 'created_at';
                $this->direction = 'desc';

                break;
        }

        $this->sortBy = $sortBy;
    }

    public function render()
    {
        return view('shopper::livewire.collections.products', [
            'products' => $this->collection
                ->products()
                ->orderBy($this->sortValue, $this->direction)
                ->get(),
        ]);
    }
}
