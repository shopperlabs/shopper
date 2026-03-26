<x-shopper::slideover-card>
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
                <x-livewire-slide-over::close-icon />
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
</x-shopper::slideover-card>
