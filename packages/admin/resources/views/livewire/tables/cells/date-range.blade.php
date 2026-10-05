@php
    $start = $getRecord()->{$startColumn};
    $end = $getRecord()->{$endColumn};
@endphp

<div class="flex shrink-0 items-center gap-3">
    <p class="text-sh-fg-muted text-sm leading-6">
        @if ($end)
            <span>{{ $start->format('d M, Y') }}</span>
            <span>-</span>
            <span>{{ $end->format('d M, Y') }}</span>
        @else
            <span>
                {{ __('shopper::words.from_date', ['date' => $start->format('d M, Y')]) }}
            </span>
        @endif
    </p>
</div>
