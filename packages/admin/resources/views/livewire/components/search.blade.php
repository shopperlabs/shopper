<div x-data class="w-full cursor-pointer lg:max-w-sm" @click="$dispatch('toggle-spotlight')">
    <div class="relative">
        <div
            class="w-full rounded-md border border-sh-border bg-sh-surface py-2 pr-12 pl-4 text-sm leading-5 text-sh-fg-muted hover:bg-sh-muted"
        >
            {{ __('shopper::words.search') }}
        </div>
        <div class="pointer-events-none absolute inset-y-0 right-0 flex py-1.5 pr-2">
            <kbd
                class="inline-flex items-center rounded-md border border-sh-border px-2 font-sans text-sm font-medium text-sh-fg-muted"
            >
                ⌘K
            </kbd>
        </div>
    </div>
</div>
