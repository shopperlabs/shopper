<div class="flex h-full flex-col divide-y divide-gray-100 dark:divide-white/10">
    <header class="p-4">
        <div class="flex items-start justify-between">
            <h2 class="text-lg font-medium text-gray-900 dark:text-white">
                {{ __('shopper::pages/products.modals.variants.add') }}
            </h2>
            <div class="ml-3 flex h-7 items-center gap-2">
                <x-shopper::escape />
                <button
                    type="button"
                    class="rounded-md bg-white text-gray-400 hover:text-gray-500 outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:bg-gray-900 dark:text-gray-500 dark:ring-offset-gray-900 dark:hover:text-gray-300"
                    wire:click="$dispatch('closePanel')"
                >
                    <span class="sr-only">Close panel</span>
                    <x-untitledui-x class="size-6" stroke-width="1.5" aria-hidden="true" />
                </button>
            </div>
        </div>
    </header>

    <form wire:submit="save" class="h-0 flex-1 overflow-y-auto [&>div]:h-full">
        {{ $this->form }}
    </form>
</div>
