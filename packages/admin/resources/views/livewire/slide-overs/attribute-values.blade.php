<div class="flex h-full flex-col divide-y divide-gray-200 dark:divide-white/10">
    <div class="h-0 flex-1 overflow-y-auto py-6">
        <header class="px-4 sm:px-6">
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-2">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white">
                        {{ __('shopper::pages/attributes.values.title') }}
                    </h2>
                    <x-filament::badge color="gray">
                        {{ $attribute->name }}
                    </x-filament::badge>
                </div>
                <div class="ml-3 flex h-7 items-center gap-2">
                    <x-shopper::escape />
                    <button
                        type="button"
                        class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:bg-gray-900 dark:text-gray-500 dark:ring-offset-gray-900 dark:hover:text-gray-300"
                        wire:click="$dispatch('closePanel')"
                    >
                        <span class="sr-only">Close panel</span>
                        <x-untitledui-x class="size-6" stroke-width="1.5" aria-hidden="true" />
                    </button>
                </div>
            </div>
            <div class="mt-1">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ __('shopper::pages/attributes.values.description') }}
                </p>
            </div>
        </header>
        <div class="mt-10 flex-1 px-4 sm:px-6">
            {{ $this->table }}
        </div>
    </div>
</div>
