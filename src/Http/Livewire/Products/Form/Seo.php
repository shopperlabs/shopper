<?php

namespace Shopper\Framework\Http\Livewire\Products\Form;

use Livewire\Component;
use Illuminate\Validation\Rule;
use Shopper\Framework\Traits\WithSeoAttributes;
use Shopper\Framework\Repositories\Ecommerce\ProductRepository;

class Seo extends Component
{
    use WithSeoAttributes;

    public $product;

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

    /**
     * Store/Update a entry to the storage.
     */
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

        $this->notify([
            'title' => __('Updated'),
            'message' => __('Product SEO successfully updated!'),
        ]);
    }

    public function render()
    {
        return view('shopper::livewire.products.forms.form-seo');
    }
}
