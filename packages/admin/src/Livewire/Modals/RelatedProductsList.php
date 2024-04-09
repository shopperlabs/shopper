<?php

declare(strict_types=1);

namespace Shopper\Livewire\Modals;

use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use LivewireUI\Modal\ModalComponent;
use Shopper\Core\Repositories\Store\ProductRepository;

class RelatedProductsList extends ModalComponent
{
    public $product;

    public string $search = '';

    public array $exceptProductIds = [];

    public array $selectedProducts = [];

    public function mount(int $productId, array $ids = []): void
    {
        $this->product = (new ProductRepository())->getById($productId);
        $this->exceptProductIds = $ids;
    }

    public static function modalMaxWidth(): string
    {
        return '3xl';
    }

    public function getProductsProperty()
    {
        return (new ProductRepository())
            ->where('name', '%' . $this->search . '%', 'like')
            ->whereNull('parent_id')
            ->get(['name', 'price_amount', 'id'])
            ->except($this->exceptProductIds);
    }

    public function addSelectedProducts(): void
    {
        $currentProducts = $this->product->relatedProducts->pluck('id')->toArray();
        $this->product->relatedProducts()->sync(array_merge($this->selectedProducts, $currentProducts));

        $this->dispatch('productHasUpdated');

        Notification::make()
            ->title(__('shopper::layout.status.added'))
            ->body(__('shopper::pages/products.notifications.related_added'))
            ->success()
            ->send();

        $this->closeModal();
    }

    public function render(): View
    {
        return view('shopper::livewire.modals.related-products-list');
    }
}
