<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Controllers\Ecommerce;

use Illuminate\Contracts\View\View;
use Shopper\Framework\Http\Controllers\ShopperBaseController;
use Shopper\Framework\Models\Shop\Order\Order;

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
