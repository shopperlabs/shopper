<x-shopper::slideover-card class="divide-sh-border divide-y">
    <header class="p-4">
        <div class="flex items-start justify-between">
            <h2 class="text-sh-fg text-lg font-medium">
                {{ __('shopper::pages/products.import.csv_title') }}
            </h2>
            <x-livewire-slide-over::close-icon />
        </div>
    </header>

    <form wire:submit="store" class="h-0 flex-1 overflow-y-auto [&>div]:h-full">
        {{ $this->form }}
    </form>
</x-shopper::slideover-card>
