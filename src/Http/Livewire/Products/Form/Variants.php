<?php

namespace Shopper\Framework\Http\Livewire\Products\Form;

use Exception;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Database\Eloquent\Builder;
use Shopper\Framework\Traits\WithUploadProcess;
use Shopper\Framework\Events\Products\ProductRemoved;
use Shopper\Framework\Http\Livewire\Products\WithAttributes;
use Shopper\Framework\Repositories\Ecommerce\ProductRepository;

class Variants extends Component
{
    use WithPagination, WithFileUploads, WithAttributes, WithUploadProcess;

    public string $search = '';
    public $quantity;
    public $product;
    public string $currency;

    protected $listeners = ['onVariantAdded' => 'render'];

    public function mount($product, string $currency)
    {
        $this->product = $product;
        $this->currency = $currency;
    }

    public function paginationView(): string
    {
        return 'shopper::livewire.wire-pagination-links';
    }

    /**
     * Remove a record to the database.
     *
     * @throws Exception
     */
    public function remove(int $id)
    {
        $product = (new ProductRepository())->getById($id);

        event(new ProductRemoved($product));

        $product->forceDelete();

        $this->dispatchBrowserEvent('item-removed');

        $this->notify([
            'title' => __('Deleted'),
            'message' => __('The variation has successfully removed!'),
        ]);
    }

    public function render()
    {
        return view('shopper::livewire.products.forms.form-variants', [
            'variants' => (new ProductRepository())
                ->makeModel()
                ->where(function (Builder $query) {
                    $query->where('name', 'like', '%' . $this->search . '%');
                    $query->where('parent_id', $this->product->id);
                })
                ->orderBy('created_at', 'desc')
                ->paginate(10),
        ]);
    }
}
