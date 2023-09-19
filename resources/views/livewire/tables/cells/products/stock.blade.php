<div class="flex items-center">
    @if($row->variations_count > 0)
        <x-shopper::stock-badge :stock="$row->variationsStock" />
        {{ __('in stock for :count variant(s)', ['count' => $row->variations_count]) }}
    @else
        <span @class([
            'mr-2 text-xs px-1.5 inline-flex leading-5 font-semibold rounded-full',
            'bg-red-100 text-red-800' => $row->stock < 10,
            'bg-green-100 text-green-800' => $row->stock >= 10,
            ])
        >
            {{ $row->stock }}
        </span>
        {{ __('in stock') }}
    @endif
</div>
