@if($order->items_count === 1)
    <span class="font-medium text-gray-700 dark:text-gray-300">
        {{ $order->items->first()->name }}
    </span>
@endif
@if($order->items_count > 1)
    <span class="font-medium text-gray-700 dark:text-gray-300">{{ $order->items->first()->name }}</span>
    + {{ __(':number more', ['number' => $order->items_count - 1]) }}
@endif
