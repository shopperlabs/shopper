<?php

declare(strict_types=1);

namespace Shopper\Livewire\Modals;

use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Shopper\Core\Models\Product;
use Shopper\Core\Repositories\ProductRepository;
use Shopper\Livewire\Components\ModalComponent;

/**
 * @property-read Collection<int, Product> $products
 */
class RelatedProductsList extends ModalComponent
{
    /**
     * @var Product
     */
    public $product;

    public string $search = '';

    /** @var array<int> */
    #[Locked]
    public array $exceptProductIds = [];

    /** @var array<int> */
    public array $selectedProducts = [];

    /**
     * @param  array<int>  $ids
     */
    public function mount(int $productId, array $ids = []): void
    {
        /** @var Product $product */
        $product = (new ProductRepository)->getById($productId);
        $this->product = $product;
        $this->exceptProductIds = $ids;
    }

    /**
     * @return Collection<int, Product>
     *
     * @throws \Shopper\Core\Exceptions\ModelRepositoryException
     */
    #[Computed]
    public function products(): Collection
    {
        return (new ProductRepository) // @phpstan-ignore-line
            ->query()
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
        /** @var array<int> $currentProducts */
        $currentProducts = $this->product->relatedProducts->pluck('id')->toArray();

        $this->product->relatedProducts()->sync(array_merge($this->selectedProducts, $currentProducts));

        Notification::make()
            ->title(__('shopper::layout.status.added'))
            ->body(__('shopper::pages/products.notifications.related_added'))
            ->success()
            ->send();

        $this->redirect(
            route('shopper.products.edit', ['product' => $this->product, 'tab' => 'related']),
            navigate: true
        );
    }

    public function render(): View
    {
        return view('shopper::livewire.modals.related-products-list');
    }
}
