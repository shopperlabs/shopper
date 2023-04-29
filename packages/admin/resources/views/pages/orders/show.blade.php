<x-shopper::layouts.app :title="__('shopper::pages/orders.show_title', ['number' => $order->number])">

    <livewire:shopper-orders.show :order="$order" />

</x-shopper::layouts.app>
