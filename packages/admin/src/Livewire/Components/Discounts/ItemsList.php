<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Discounts;

use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;
use Shopper\Core\Models\Contracts\Product;

class ItemsList extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    #[Locked]
    public string $type = 'products';

    /** @var array<int> */
    public array $selected = [];

    /**
     * @param  array<int>  $selected
     */
    public function mount(string $type, array $selected = []): void
    {
        $this->type = $type;
        $this->selected = array_values(array_unique(array_map('intval', $selected)));
    }

    /**
     * @param  array<int>  $ids
     */
    #[On('discount.products.added')]
    public function addProducts(array $ids): void
    {
        if ($this->type !== 'products') {
            return;
        }

        $this->merge($ids);
    }

    /**
     * @param  array<int>  $ids
     */
    #[On('discount.customers.added')]
    public function addCustomers(array $ids): void
    {
        if ($this->type !== 'customers') {
            return;
        }

        $this->merge($ids);
    }

    public function removeItem(int $id): void
    {
        $this->selected = array_values(array_filter(
            $this->selected,
            fn (int $existing): bool => $existing !== $id,
        ));

        $this->dispatchChanged();
    }

    public function isProducts(): bool
    {
        return $this->type === 'products';
    }

    public function pickerComponent(): string
    {
        return $this->isProducts()
            ? 'shopper-slide-overs.discount-products-picker'
            : 'shopper-slide-overs.discount-customers-picker';
    }

    /**
     * @return EloquentCollection<int, Model>
     */
    #[Computed]
    public function items(): EloquentCollection
    {
        if ($this->selected === []) {
            return new EloquentCollection;
        }

        if ($this->isProducts()) {
            return resolve(Product::class)::query()
                ->scopes('publish')
                ->whereIn('id', $this->selected)
                ->get();
        }

        return config('auth.providers.users.model')::query()
            ->customers()
            ->whereIn('id', $this->selected)
            ->get();
    }

    public function render(): View
    {
        return view('shopper::livewire.components.discounts.items-list');
    }

    /**
     * @param  array<int>  $ids
     */
    private function merge(array $ids): void
    {
        $this->selected = array_values(array_unique(array_merge(
            $this->selected,
            array_map('intval', $ids),
        )));

        $this->dispatchChanged();
    }

    private function dispatchChanged(): void
    {
        $this->dispatch(
            'discount.items.changed',
            type: $this->type,
            ids: $this->selected,
        );
    }
}
