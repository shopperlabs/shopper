<div>
    @if ($import)
        <div
            wire:poll.5s
            class="border-sh-border bg-sh-muted/50 mt-6 flex items-center gap-3 rounded-xl border px-4 py-3"
        >
            <x-shopper::loader class="text-primary-500 size-4" aria-hidden="true" />
            <p class="text-sh-fg text-sm">
                <span class="font-medium">{{ __('shopper::pages/products.import.progress.running') }}</span>
                @if ($import->total_products > 0)
                    <span class="text-sh-fg-muted">
                        {{ __('shopper::pages/products.import.progress.count', ['imported' => $import->imported_count, 'total' => $import->total_products]) }}
                    </span>
                @endif
            </p>
        </div>
    @endif
</div>
