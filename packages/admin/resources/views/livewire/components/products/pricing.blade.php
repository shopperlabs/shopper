<x-shopper::container>
    <div>
        <x-filament::section.heading>
            {{ __('shopper::pages/products.pricing.title') }}
        </x-filament::section.heading>
        <x-filament::section.description class="mt-1 max-w-2xl">
            {{ __('shopper::pages/products.pricing.description') }}
        </x-filament::section.description>
    </div>

    <div class="mt-8">
        {{ $this->table }}
    </div>
</x-shopper::container>
