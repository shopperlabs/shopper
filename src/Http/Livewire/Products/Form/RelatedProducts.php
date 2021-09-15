<?php

namespace Shopper\Framework\Http\Livewire\Products\Form;

use Livewire\Component;
use Illuminate\Database\Eloquent\Builder;
use Shopper\Framework\Repositories\Ecommerce\ProductRepository;

class RelatedProducts extends Component
{
    /**
     * Product Model.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    public $product;

    /**
     * @var \Illuminate\Database\Eloquent\Collection
     */
    public $relatedProducts;

    /**
     * Component Mount method.
     *
     * @param \Illuminate\Database\Eloquent\Model $product
     */
    public function mount($product)
    {
        $this->product = $product;
        $this->relatedProducts = $product->relatedProducts;
    }

    public function add(int $id)
    {
        $this->product->relatedProducts()->attach([$id]);
        $this->relatedProducts = $this->product->relatedProducts;

        $this->notify([
            'title' => __('Added'),
            'message' => __('Similar product added successfully'),
        ]);

        return true;
    }

    public function remove(int $id)
    {
        $this->product->relatedProducts()->detach([$id]);
        $this->relatedProducts = $this->product->relatedProducts;

        $this->notify([
            'title' => __('Removed'),
            'message' => __('Similar product removed successfully'),
        ]);

        return true;
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     *
     * @throws \Shopper\Framework\Exceptions\GeneralException
     */
    public function render()
    {
        return view('shopper::livewire.products.forms.form-related', [
            'products' => (new ProductRepository())
                ->makeModel()
                ->where(function (Builder $query) {
                    $query->where('id', '<>', $this->product->id);
                })
                ->get(),
        ]);
    }
}
