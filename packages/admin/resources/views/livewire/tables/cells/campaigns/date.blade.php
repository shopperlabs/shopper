@php
    $campaign = $getRecord();
@endphp

<div class="flex shrink-0 items-center gap-3">
    <p class="text-sm leading-6 text-sh-fg-muted">
        @if ($campaign->ends_at)
            <span>{{ $campaign->starts_at->format('d M, Y') }}</span>
            <span>-</span>
            <span>{{ $campaign->ends_at->format('d M, Y') }}</span>
        @else
            <span>
                {{ __('shopper::words.from_date', ['date' => $campaign->starts_at->format('d M, Y')]) }}
            </span>
        @endif
    </p>
</div>
