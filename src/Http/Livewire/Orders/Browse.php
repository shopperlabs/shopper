<?php

declare(strict_types=1);

namespace Shopper\Framework\Http\Livewire\Orders;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Shopper\Framework\Models\Shop\Order\Order;
use Shopper\Framework\Models\Shop\Order\OrderItem;
use Shopper\Framework\Models\Shop\Product\Product;

class Browse extends Component
{
    public function render(): View
    {
//        $order = Order::query()->create([
//            'number' => generate_number(),
//            'price_amount' => 28600,
//            'currency' => shopper_currency(),
//            'shipping_total' => 50,
//            'shipping_method' => 'Vero Shop',
//            'shipping_address_id' => 1,
//            'payment_method_id' => 1,
//            'user_id' => 2,
//        ]);
//
//        $product = Product::find(3);
//        OrderItem::query()->create([
//            'name' => $product->name,
//            'sku' => $product->sku,
//            'quantity' => 2,
//            'unit_price_amount' => $product->price_amount,
//            'order_id' => $order->id,
//            'product_id' => $product->id,
//            'product_type' => config('shopper.system.models.product'),
//        ]);

        return view('shopper::livewire.orders.browse', [
            'total' => Order::query()->count(),
        ]);
    }
}
