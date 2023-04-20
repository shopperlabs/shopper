@if($row->items_count === 1)
    <span class="font-medium text-secondary-700 dark:text-secondary-300">
        {{ $row->items->first()->name }}
    </span>
@endif
@if($row->items_count > 1)
    <span class="font-medium text-secondary-700 dark:text-secondary-300">{{ $row->items->first()->name }}</span>
    + {{ __(':number more', ['number' => $row->items_count - 1]) }}
@endif
