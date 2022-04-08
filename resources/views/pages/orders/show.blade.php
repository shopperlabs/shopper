<x-shopper::layouts.app :title="__('Detail Order ~ :number', ['number' => $order->number])">

    <livewire:shopper-orders.show :order="$order" />

</x-shopper::layouts.app>
