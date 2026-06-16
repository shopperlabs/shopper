<?php

declare(strict_types=1);

namespace Shopper\Livewire\Concerns;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Shopper\Core\Models\Contracts\Product;

trait InteractsWithDiscountItems
{
    public bool $showAllProducts = false;

    public bool $showAllCustomers = false;

    protected int $itemsInlineLimit = 12;

    /**
     * @param  array<int>  $ids
     */
    #[On('discount.products.added')]
    public function addProductsToDiscount(array $ids): void
    {
        $current = (array) data_get($this->data, 'products', []);
        $merged = array_values(array_unique(array_merge(
            array_map('intval', $current),
            array_map('intval', $ids),
        )));

        data_set($this->data, 'products', $merged);
    }

    /**
     * @param  array<int>  $ids
     */
    #[On('discount.customers.added')]
    public function addCustomersToDiscount(array $ids): void
    {
        $current = (array) data_get($this->data, 'customers', []);
        $merged = array_values(array_unique(array_merge(
            array_map('intval', $current),
            array_map('intval', $ids),
        )));

        data_set($this->data, 'customers', $merged);
    }

    public function toggleShowAllProducts(): void
    {
        $this->showAllProducts = ! $this->showAllProducts;
    }

    public function toggleShowAllCustomers(): void
    {
        $this->showAllCustomers = ! $this->showAllCustomers;
    }

    public function getItemsInlineLimit(): int
    {
        return $this->itemsInlineLimit;
    }

    public function removeProductFromDiscount(int $productId): void
    {
        $current = (array) data_get($this->data, 'products', []);

        data_set(
            $this->data,
            'products',
            array_values(array_filter(
                array_map('intval', $current),
                fn (int $id): bool => $id !== $productId,
            )),
        );
    }

    public function removeCustomerFromDiscount(int $customerId): void
    {
        $current = (array) data_get($this->data, 'customers', []);

        data_set(
            $this->data,
            'customers',
            array_values(array_filter(
                array_map('intval', $current),
                fn (int $id): bool => $id !== $customerId,
            )),
        );
    }

    /**
     * @return EloquentCollection<int, Model>
     */
    #[Computed]
    public function selectedProducts(): EloquentCollection
    {
        $ids = array_values(array_filter(
            array_map('intval', (array) data_get($this->data, 'products', [])),
        ));

        if ($ids === []) {
            return new EloquentCollection;
        }

        return resolve(Product::class)::query()
            ->with('media')
            ->whereIn('id', $ids)
            ->get();
    }

    /**
     * @return EloquentCollection<int, Model>
     */
    #[Computed]
    public function selectedCustomers(): EloquentCollection
    {
        $ids = array_values(array_filter(
            array_map('intval', (array) data_get($this->data, 'customers', [])),
        ));

        if ($ids === []) {
            return new EloquentCollection;
        }

        return config('auth.providers.users.model')::query()
            ->whereIn('id', $ids)
            ->get();
    }
}
