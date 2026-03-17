<div class="flex h-full flex-col overflow-hidden rounded-xl bg-white shadow-xl ring-1 ring-gray-200 dark:bg-gray-900 dark:ring-white/10">
    <div class="h-0 flex-1 overflow-y-auto py-6">
        <header class="px-4 sm:px-6">
            <div class="flex items-start justify-between gap-3">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white">
                    {{ __('shopper::pages/products.related.modal.title') }}
                </h2>
                <div class="flex h-7 items-center gap-2">
                    <x-shopper::escape />
                    <button
                        type="button"
                        class="rounded-md bg-white text-gray-400 hover:text-gray-500 dark:bg-gray-900 dark:text-gray-500 dark:hover:text-gray-300"
                        wire:click="$dispatch('closePanel')"
                    >
                        <span class="sr-only">Close panel</span>
                        <x-untitledui-x class="size-6" stroke-width="1.5" aria-hidden="true" />
                    </button>
                </div>
            </div>
        </header>
        <div class="mt-6 flex-1 px-4 sm:px-6">
            {{ $this->table }}
        </div>
    </div>
</div>
