<x-shopper::slideover-card class="divide-y divide-sh-border">
    <header class="p-4">
        <div class="flex items-start justify-between">
            <h2 class="text-lg font-medium text-sh-fg">
                {{ __('shopper::pages/products.attributes.choose') }}
            </h2>
            <x-livewire-slide-over::close-icon />
        </div>
    </header>

    <form wire:submit="store" class="h-0 flex-1 overflow-y-auto [&>div]:h-full">
        {{ $this->form }}
    </form>
</x-shopper::slideover-card>
