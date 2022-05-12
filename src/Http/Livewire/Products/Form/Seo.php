<?php

namespace Shopper\Framework\Http\Livewire\Products\Form;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Shopper\Framework\Repositories\Ecommerce\ProductRepository;
use Shopper\Framework\Traits\WithSeoAttributes;
use WireUi\Traits\Actions;

class Seo extends Component
{
    use Actions, WithSeoAttributes;

    public Model $product;
    public int $productId;
    public string $slug;

    public $seoAttributes = [
        'name' => 'name',
        'description' => 'description',
    ];

    public function mount($product)
    {
        $this->product = $product;
        $this->productId = $product->id;
        $this->slug = $product->slug;
        $this->seoTitle = $product->seo_title;
        $this->seoDescription = $product->seo_description;
    }

    public function store()
    {
        $this->validate([
            'slug' => [
                'required',
                Rule::unique(shopper_table('products'), 'sku')->ignore($this->productId),
            ],
        ]);

        (new ProductRepository())->getById($this->productId)->update([
            'slug' => str_slug($this->slug),
            'seo_title' => $this->seoTitle,
            'seo_description' => str_limit($this->seoDescription, 157),
        ]);

        $this->emit('productHasUpdated', $this->productId);

        $this->notification()->success(
            __('shopper::layout.status.updated'),
            __('shopper::pages/products.notifications.seo_update')
        );
    }

    public function render()
    {
        return view('shopper::livewire.products.forms.form-seo');
    }
}
