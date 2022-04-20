<?php

namespace Shopper\Framework\Http\Livewire\Products\Form;

use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use WireUi\Traits\Actions;

class RelatedProducts extends Component
{
    use Actions;

    public Model $product;

    public function mount($product)
    {
        $this->product = $product;
    }

    public function render()
    {
        return view('shopper::livewire.products.forms.form-related');
    }
}
