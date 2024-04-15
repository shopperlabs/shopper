<?php

declare(strict_types=1);

namespace Shopper\Livewire\Modals;

use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use LivewireUI\Modal\ModalComponent;
use Shopper\Core\Repositories\Store\CollectionRepository;
use Shopper\Core\Repositories\Store\ProductRepository;

class CollectionProductsList extends ModalComponent
{
    public $collection;

    public string $search = '';

    public array $exceptProductIds;

    public array $selectedProducts = [];

    public function mount(int $collectionId, array $exceptProductIds = []): void
    {
        $this->collection = (new CollectionRepository())->getById($collectionId);
        $this->exceptProductIds = $exceptProductIds;
    }

    public static function modalMaxWidth(): string
    {
        return '3xl';
    }

    #[Computed]
    public function products(): Collection
    {
        return (new ProductRepository())
            ->where('name', '%' . $this->search . '%', 'like')
            ->whereNull('parent_id')
            ->get(['name', 'price_amount', 'id'])
            ->except($this->exceptProductIds);
    }

    public function addSelectedProducts(): void
    {
        $currentProducts = $this->collection->products->pluck('id')->toArray();
        $this->collection->products()->sync(array_merge($this->selectedProducts, $currentProducts));

        $this->dispatch('onProductsAddInCollection');

        Notification::make()
            ->title(__('shopper::pages/collections.modal.success_message'))
            ->success()
            ->send();

        $this->closeModal();
    }

    public function render(): View
    {
        return view('shopper::livewire.modals.products-lists');
    }
}
