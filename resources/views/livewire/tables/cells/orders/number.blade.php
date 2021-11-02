@can('read_orders')
    <a href="{{ route('shopper.orders.show', $order) }}" class="truncate font-medium text-gray-900 hover:text-gray-700 dark:text-white dark:hover:text-gray-300">
        <span>{{ $order->number }}</span>
    </a>
@else
    <span>{{ $order->number }}</span>
@endcan
