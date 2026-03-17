<div class="flex h-full flex-col overflow-hidden rounded-xl bg-white shadow-xl ring-1 ring-gray-200 dark:bg-gray-900 dark:ring-white/10 divide-y divide-gray-100 dark:divide-white/10">
    <header class="p-4">
        <div class="flex items-start justify-between">
            <h2 class="text-lg font-medium text-gray-900 dark:text-white">
                {{ __('shopper::pages/products.attributes.choose') }}
            </h2>
            <x-livewire-slide-over::close-icon />
        </div>
    </header>

    <form wire:submit="store" class="h-0 flex-1 overflow-y-auto [&>div]:h-full">
        {{ $this->form }}
    </form>
</div>
