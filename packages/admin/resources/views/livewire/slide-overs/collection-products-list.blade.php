<div class="flex h-full flex-col">
    <div class="h-0 flex-1 overflow-y-auto py-6">
        <header class="px-4 sm:px-6">
            <div class="flex items-start justify-between">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white">
                    {{ __('shopper::pages/collections.modal.title') }}
                </h2>
                <div class="ml-3 flex h-7 items-center gap-2">
                    <x-shopper::escape />
                    <button
                        type="button"
                        class="focus:ring-primary-500 rounded-md bg-white text-gray-400 hover:text-gray-500 focus:ring-2 focus:ring-offset-2 focus:outline-none dark:bg-gray-900 dark:text-gray-500 dark:ring-offset-gray-900 dark:hover:text-gray-300"
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
