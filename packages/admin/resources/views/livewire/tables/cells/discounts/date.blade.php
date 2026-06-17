@php
    $discount = $getRecord()
@endphp

<p class="text-sm leading-6 text-gray-500 dark:text-gray-400">
    @if ($discount->end_at)
        <span>{{ $discount->start_at->format('d M, Y') }}</span>
        <span>-</span>
        <span>{{ $discount->end_at->format('d M, Y') }}</span>
    @else
        <span>
            {{ __('shopper::words.from_date', ['date' => $discount->start_at->format('d M, Y')]) }}
        </span>
    @endif
</p>
