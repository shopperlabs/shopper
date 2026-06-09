<x-shopper::slideover-card>
    <div class="h-0 flex-1 overflow-y-auto py-6">
        <header class="px-4 sm:px-6">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <h2 class="text-lg font-medium text-sh-fg">
                        {{ __('shopper::pages/discounts.customers_picker.title') }}
                    </h2>
                    <p class="text-sh-fg-secondary mt-1 text-sm">
                        {{ __('shopper::pages/discounts.customers_picker.description') }}
                    </p>
                </div>
                <x-livewire-slide-over::close-icon />
            </div>
        </header>
        <div class="mt-6 flex-1 px-4 sm:px-6">
            {{ $this->table }}
        </div>
    </div>
</x-shopper::slideover-card>
