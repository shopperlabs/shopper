<x-shopper::container class="flex min-h-full flex-1 flex-col items-center justify-center py-24">
    <div class="flex flex-col items-center justify-center">
        <div class="bg-sh-muted ring-sh-border rounded-full p-1 ring-1">
            <div
                class="bg-sh-surface ring-sh-border flex items-center justify-center space-y-2 rounded-full p-2 shadow ring-1"
            >
                <x-phosphor-shield-check-duotone class="size-8" aria-hidden="true" />
            </div>
        </div>

        <p class="text-sh-fg-muted mt-6 font-semibold tracking-widest uppercase">403</p>

        <h1 class="font-heading text-sh-fg mt-2 text-3xl font-bold">
            {{ __('shopper::errors.403.title') }}
        </h1>

        <p class="text-sh-fg-muted mt-3 max-w-md text-center text-base">
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
