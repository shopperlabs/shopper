<?php

declare(strict_types=1);

namespace Shopper\Livewire\Components\Orders;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Shopper\Core\Models\Contracts\Order;
use Shopper\Core\Models\Contracts\ShopperUser;

/**
 * @property-read ShopperUser|null $customer
 */
class OrderCustomer extends Component
{
    public Order $order;

    #[Computed]
    public function customer(): ?ShopperUser
    {
        $userModel = config('auth.providers.users.model');

        return $userModel::query()
            ->withCount('orders')
            ->find($this->order->customer_id);
    }

    public function render(): View
    {
        $this->order->loadMissing(['shippingAddress', 'billingAddress']);

        return view('shopper::livewire.components.orders.order-customer', [
            'shippingAddress' => $this->order->shippingAddress,
            'billingAddress' => $this->order->billingAddress,
        ]);
    }
}
