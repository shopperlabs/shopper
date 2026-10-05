@php
    $firstItem = $order->items->first();
@endphp

@if ($firstItem === null)
    <span class="text-sh-fg-muted text-sm">—</span>
@else
    @php
        $others = $order->items->count() - 1;
        $more = $others > 0 ? ' + ' . __('shopper::words.number_more', ['number' => $others]) : '';
        $label = $firstItem->name . $more;
        $isTruncated = mb_strlen($label) > 50;
    @endphp

    <div class="flex items-center gap-2">
        <img
            class="size-8 rounded-full object-cover"
            src="{{ $firstItem->product?->getThumbnailUrl() }}"
            alt="Avatar {{ $firstItem->product?->name }}"
        />

        <span
            @if ($isTruncated) x-data x-tooltip="{{ \Illuminate\Support\Js::from($label) }}" @endif
            class="text-sh-fg-secondary max-w-[50ch] truncate font-medium"
        >
            {{ $label }}
        </span>
    </div>
@endif
