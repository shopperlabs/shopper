<?php

declare(strict_types=1);

namespace Shopper\Livewire\Modals;

use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Shopper\Core\Repositories\CollectionRepository;
use Shopper\Core\Repositories\ProductRepository;
use Shopper\Livewire\Components\ModalComponent;

class CollectionProductsList extends ModalComponent
{
    public $collection;

    public string $search = '';

    #[Locked]
    public array $exceptProductIds = [];

    public array $selectedProducts = [];

    public function mount(int $collectionId, array $exceptProductIds = []): void
    {
        $this->collection = (new CollectionRepository)->getById($collectionId);
        $this->exceptProductIds = $exceptProductIds;
    }

    public static function modalMaxWidth(): string
    {
        return 'xl';
    }

    #[Computed]
    public function products(): Collection
    {
        return (new ProductRepository) // @phpstan-ignore-line
            ->query()
            ->where(
                column: 'name',
                operator: 'like',
                value: '%' . $this->search . '%'
            )
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
