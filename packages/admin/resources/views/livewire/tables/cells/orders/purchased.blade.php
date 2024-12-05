@php
    $firstItem = $order->items->first();
@endphp

<div class="flex items-center gap-2">
    <img
        class="size-8 rounded-full object-cover"
        src="{{ $firstItem->product->getFirstMediaUrl(config('shopper.media.storage.thumbnail_collection')) }}"
        alt="Avatar {{ $firstItem->product->name }}"
    />

    @if ($order->items->count() === 1)
        <span class="font-medium text-gray-700 dark:text-gray-300">
            {{ $firstItem->name }}
        </span>
    @endif

    @if ($order->items->count() > 1)
        <span class="font-medium text-gray-700 dark:text-gray-300">
            {{ $firstItem->name }} + {{ __('shopper::words.number_more', ['number' => $order->items->count() - 1]) }}
        </span>
    @endif
</div>
