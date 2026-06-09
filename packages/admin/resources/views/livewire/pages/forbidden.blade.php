<x-shopper::container class="flex flex-1 min-h-full flex-col items-center justify-center py-24">
    <div class="flex flex-col justify-center items-center">

        <div class="bg-sh-muted rounded-full p-1 ring-1 ring-sh-border">
            <div class="bg-sh-surface rounded-full ring-1 ring-sh-border shadow space-y-2 p-2 flex items-center justify-center">
                <x-phosphor-shield-check-duotone class="size-8" aria-hidden="true" />
            </div>
        </div>

        <p class="mt-6 font-semibold uppercase tracking-widest text-sh-fg-muted">
            403
        </p>

        <h1 class="mt-2 text-3xl font-bold font-heading text-sh-fg">
            {{ __('shopper::errors.403.title') }}
        </h1>

        <p class="mt-3 max-w-md text-center text-base text-sh-fg-muted">
            {{ __('shopper::errors.403.description') }}
        </p>

        <div class="mt-8 flex items-center justify-center gap-3">
            <x-filament::button :href="route('shopper.dashboard')" tag="a" wire:navigate>
                <x-untitledui-arrow-left class="-ml-0.5 size-4" aria-hidden="true" stroke-width="1.5" />
                {{ __('shopper::errors.403.back') }}
            </x-filament::button>

            <x-filament::button type="button" color="gray" onclick="history.back()">
                {{ __('shopper::errors.403.go_back') }}
            </x-filament::button>
        </div>
    </div>
</x-shopper::container>
