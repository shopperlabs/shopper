<?php

declare(strict_types=1);

namespace Shopper\Livewire\Modals;

use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Shopper\Core\Repositories\ProductRepository;
use Shopper\Livewire\Components\ModalComponent;

class RelatedProductsList extends ModalComponent
{
    public $product;

    public string $search = '';

    #[Locked]
    public array $exceptProductIds = [];

    public array $selectedProducts = [];

    public function mount(int $productId, array $ids = []): void
    {
        $this->product = (new ProductRepository)->getById($productId);
        $this->exceptProductIds = $ids;
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
