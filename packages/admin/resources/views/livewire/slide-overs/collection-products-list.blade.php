<div class="flex h-full flex-col">
    <div class="h-0 flex-1 overflow-y-auto py-6">
        <header class="px-4 sm:px-6">
            <div class="flex items-start justify-between">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white">
                    {{ __('shopper::pages/collections.modal.title') }}
                </h2>
                <x-shopper::close-icon />
            </div>
        </header>
        <div class="mt-6 flex-1 px-4 sm:px-6">
            {{ $this->table }}
        </div>
    </div>
</div>
