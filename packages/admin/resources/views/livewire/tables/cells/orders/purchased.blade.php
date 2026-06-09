@php
    $firstItem = $order->items->first();
@endphp

@if ($firstItem === null)
    <span class="text-sm text-sh-fg-muted">—</span>
@else
    @php
        $label = $order->items->count() > 1
            ? $firstItem->name.' + '.__('shopper::words.number_more', ['number' => $order->items->count() - 1])
            : $firstItem->name;
        $isTruncated = mb_strlen($label) > 50;
    @endphp

    <div class="flex items-center gap-2">
        <img
            class="size-8 rounded-full object-cover"
            src="{{ $firstItem->product?->getFirstMediaUrl(config('shopper.media.storage.thumbnail_collection')) }}"
            alt="Avatar {{ $firstItem->product?->name }}"
        />

        <span
            @if ($isTruncated) x-data x-tooltip="{{ \Illuminate\Support\Js::from($label) }}" @endif
            class="max-w-[50ch] truncate font-medium text-sh-fg-secondary"
        >
            {{ $label }}
        </span>
    </div>
@endif
