<div>
    @if ($import)
        <div
            wire:poll.5s
            class="mt-6 flex items-center gap-3 rounded-xl border border-sh-border bg-sh-muted/50 px-4 py-3"
        >
            <x-shopper::loader class="size-4 text-primary-500" aria-hidden="true" />
            <p class="text-sm text-sh-fg">
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
