<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Livewire\Modals;

use Illuminate\Contracts\View\View;
use LivewireUI\Modal\ModalComponent;
use Shopper\Framework\Repositories\Ecommerce\ProductRepository;

class DiscountProducts extends ModalComponent
{
    public string $search = '';

    public array $selectedProducts = [];

    public array $excludesIds;

    public function mount(array $excludeIds): void
    {
        $this->excludesIds = $excludeIds;
    }

    public function addSelectedProducts(): void
    {
        $this->excludesIds = $this->selectedProducts;

        $this->emit('addSelectedProducts', $this->selectedProducts);

        $this->closeModal();
    }

    public static function modalMaxWidth(): string
    {
        return '2xl';
    }

    public function render(): View
    {
        return view('shopper::livewire.modals.discount-products', [
            'products' => (new ProductRepository())
                ->where('name', '%' . $this->search . '%', 'like')
                ->get(['name', 'price_amount', 'id'])
                ->except($this->excludesIds),
        ]);
    }
}
