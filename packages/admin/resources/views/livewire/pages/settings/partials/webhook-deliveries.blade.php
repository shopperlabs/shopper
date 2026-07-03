<div class="divide-y divide-sh-border">
    @forelse ($deliveries as $delivery)
        <div class="flex items-center justify-between gap-4 py-3">
            <div class="min-w-0">
                <p class="text-sm font-medium text-sh-fg">{{ $delivery->event->name }}</p>
                <p class="text-xs text-sh-fg-muted">
                    {{ $delivery->created_at->translatedFormat('M j, Y H:i') }}
                    &middot; {{ __('shopper::pages/settings/webhooks.attempt', ['number' => $delivery->attempt_number]) }}
                </p>
            </div>
            <div class="flex shrink-0 items-center gap-2">
                @if ($delivery->response_code)
                    <span class="text-xs text-sh-fg-muted">HTTP {{ $delivery->response_code }}</span>
                @endif
                <x-filament::badge :color="$delivery->status->getColor()">
                    {{ $delivery->status->getLabel() }}
                </x-filament::badge>
            </div>
        </div>
    @empty
        <p class="py-6 text-center text-sm text-sh-fg-muted">
            {{ __('shopper::pages/settings/webhooks.no_delivery') }}
        </p>
    @endforelse
</div>
