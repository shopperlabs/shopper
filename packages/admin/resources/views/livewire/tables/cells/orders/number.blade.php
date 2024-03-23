@can('read_orders')
    <a href="{{ route('shopper.orders.show', $row) }}" class="truncate font-medium text-secondary-900 hover:text-secondary-700 dark:text-white dark:hover:text-secondary-300">
        <span>{{ $row->number }}</span>
    </a>
@else
    <span>{{ $row->number }}</span>
@endcan
