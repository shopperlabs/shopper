<?php

declare(strict_types=1);

namespace Shopper\Http\Controllers\Ecommerce;

use Illuminate\Contracts\View\View;
use Shopper\Core\Models\Order;
use Shopper\Http\Controllers\ShopperBaseController;

class OrderController extends ShopperBaseController
{
    public function index(): View
    {
        $this->authorize('browse_orders');

        return view('shopper::pages.orders.index');
    }

    public function show(Order $order): View
    {
        $this->authorize('read_orders');

        return view('shopper::pages.orders.show', [
            'order' => $order->load(['customer', 'items', 'shippingAddress', 'paymentMethod']),
        ]);
    }
}
