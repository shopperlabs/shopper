<div class="divide-sh-border divide-y">
    @forelse ($deliveries as $delivery)
        <div class="flex items-center justify-between gap-4 py-3">
            <div class="min-w-0">
                <p class="text-sh-fg text-sm font-medium">{{ $delivery->event->name }}</p>
                <p class="text-sh-fg-muted text-xs">
                    {{ $delivery->created_at->translatedFormat('M j, Y H:i') }}
                    &middot;
                    {{ __('shopper::pages/settings/webhooks.attempt', ['number' => $delivery->attempt_number]) }}
                </p>
            </div>
            <div class="flex shrink-0 items-center gap-2">
                @if ($delivery->response_code)
                    <span class="text-sh-fg-muted text-xs">HTTP {{ $delivery->response_code }}</span>
                @endif

                <x-filament::badge :color="$delivery->status->getColor()">
                    {{ $delivery->status->getLabel() }}
                </x-filament::badge>
            </div>
        </div>
    @empty
        <p class="text-sh-fg-muted py-6 text-center text-sm">
            {{ __('shopper::pages/settings/webhooks.no_delivery') }}
        </p>
    @endforelse
</div>
