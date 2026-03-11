<x-shopper::container class="flex flex-1 min-h-full flex-col items-center justify-center py-24">
    <div class="flex flex-col justify-center items-center">

        <div class="bg-gray-50 rounded-full p-1 ring-1 ring-gray-200 dark:ring-white/20 dark:bg-gray-900">
            <div class="bg-white dark:bg-gray-800 rounded-full ring-1 ring-gray-200 dark:ring-gray-700/80 shadow space-y-2 p-2 flex items-center justify-center">
                <x-phosphor-shield-check-duotone class="size-8" aria-hidden="true" />
            </div>
        </div>

        <p class="mt-6 font-semibold uppercase tracking-widest text-gray-500 dark:text-gray-400">
            403
        </p>

        <h1 class="mt-2 text-3xl font-bold font-heading text-gray-900 dark:text-white">
            {{ __('shopper::errors.403.title') }}
        </h1>

        <p class="mt-3 max-w-md text-center text-base text-gray-500 dark:text-gray-400">
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
