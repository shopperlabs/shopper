<?php

namespace Shopper\Framework\Http\Livewire\Products\Form;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Shopper\Framework\Repositories\Ecommerce\ProductRepository;

class RelatedProducts extends Component
{
    public Model $product;
    public Collection $relatedProducts;

    public function mount($product)
    {
        $this->product = $product;
        $this->relatedProducts = $product->relatedProducts;
    }

    public function handleOnSortOrderChanged($sortOrder, $previousSortOrder, $name, $from, $to)
    {
        $this->$name = $sortOrder;

        /*$this->events = collect($this->events)
            ->push("{$name}. Dragged from $from to $to. Previous:" . collect($previousSortOrder)->join(','))
            ->toArray();*/
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
