@if($row->items_count === 1)
    <span class="font-medium text-gray-700 dark:text-gray-300">
        {{ $row->items->first()->name }}
    </span>
@endif
@if($row->items_count > 1)
    <span class="font-medium text-gray-700 dark:text-gray-300">
        {{ $row->items->first()->name }} + {{ __(':number more', ['number' => $row->items_count - 1]) }}
    </span>
@endif
