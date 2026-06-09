@can('orders.read')
    <a
        href="{{ route('shopper.orders.show', $row) }}"
        class="truncate font-medium text-sh-fg hover:text-sh-fg-secondary"
    >
        <span>{{ $row->number }}</span>
    </a>
@else
    <span>{{ $row->number }}</span>
@endcan
