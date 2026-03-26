<x-shopper::slideover-card class="divide-y divide-gray-100 dark:divide-white/10">
    <header class="p-4">
        <div class="flex items-start justify-between">
            <h2 class="text-lg font-medium text-gray-900 dark:text-white">
                {{ __('shopper::pages/products.modals.variants.add') }}
            </h2>
            <x-livewire-slide-over::close-icon />
        </div>
    </header>

    <form wire:submit="save" class="h-0 flex-1 overflow-y-auto [&>div]:h-full">
        {{ $this->form }}
    </form>
</x-shopper::slideover-card>
