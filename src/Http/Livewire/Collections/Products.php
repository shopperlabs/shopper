<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Livewire\Collections;

use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
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

    public function mount($collection): void
    {
        $this->collection = $collection;
    }

    public function getProductsIdsProperty(): array
    {
        return $this->collection->products->modelKeys();
    }

    public function removeProduct(int $id): void
    {
        $this->collection->products()->detach([$id]);

        $this->emitSelf('onProductsAddInCollection');

        Notification::make()
            ->title(__('shopper::layout.forms.actions.remove'))
            ->body(__('The product have been correctly remove to this collection.'))
            ->success()
            ->send();
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

    public function render(): View
    {
        return view('shopper::livewire.collections.products', [
            'products' => $this->collection
                ->products()
                ->orderBy($this->sortValue, $this->direction)
                ->get(),
        ]);
    }
}
