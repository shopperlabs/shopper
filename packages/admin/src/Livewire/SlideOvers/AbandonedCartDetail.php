<?php

declare(strict_types=1);

namespace Shopper\Livewire\SlideOvers;

use Illuminate\Contracts\View\View;
use Shopper\Cart\Models\Cart;
use Shopper\Livewire\Components\SlideOverComponent;

class AbandonedCartDetail extends SlideOverComponent
{
    public Cart $cart;

    public static function panelMaxWidth(): string
    {
        return '3xl';
    }

    public function mount(): void
    {
        $this->authorize('browse_orders');

        $this->cart->load([
            'customer',
            'lines.purchasable.media',
            'addresses.country',
            'channel',
        ]);
    }

    public function render(): View
    {
        return view('shopper::livewire.slide-overs.abandoned-cart-detail');
    }
}
