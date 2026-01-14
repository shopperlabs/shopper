<?php

declare(strict_types=1);

namespace Shopper\Livewire\Modals;

use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Shopper\Core\Models\Contracts\Collection as CollectionContract;
use Shopper\Core\Models\Contracts\Product as ProductContract;
use Shopper\Livewire\Components\ModalComponent;

class CollectionProductsList extends ModalComponent
{
    public CollectionContract $collection;

    public string $search = '';

    /** @var array<int> */
    #[Locked]
    public array $exceptProductIds = [];

    /** @var array<int> */
    public array $selectedProducts = [];

    public static function modalMaxWidth(): string
    {
        return 'xl';
    }

    /**
     * @param  array<int>  $exceptProductIds
     */
    public function mount(?CollectionContract $collection = null, array $exceptProductIds = []): void
    {
        $this->collection = $collection;
        $this->exceptProductIds = $exceptProductIds;
    }

    /**
     * @return Collection<int, ProductContract>
     */
    #[Computed]
    public function products(): Collection
    {
        return resolve(ProductContract::class)::query()
            ->where(
                column: 'name',
                operator: 'like',
                value: '%'.$this->search.'%'
            )
            ->get(['name', 'id'])
            ->except($this->exceptProductIds);
    }

    public function addSelectedProducts(): void
    {
        $currentProducts = $this->collection->products->pluck('id')->toArray();
        $this->collection->products()->sync(array_merge($this->selectedProducts, $currentProducts));

        $this->dispatch('collection.add.product');

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
