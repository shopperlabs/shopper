<?php

declare(strict_types=1);

namespace Shopper\Livewire\Pages\Product;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Shopper\Core\Models\Contracts\Product as ProductContract;

#[Layout('shopper::components.layouts.product')]
class Pricing extends Component
{
    /** @var Model&ProductContract */
    #[Locked]
    public ProductContract $product;

    public function render(): View
    {
        return view('shopper::livewire.pages.products.pricing')
            ->title($this->product->name.' ~ '.__('shopper::words.pricing'));
    }
}
