<div>
    <x-shopper::separator />

    <div>
        <x-filament::section.heading>
            {{ __('shopper::pages/products.menu') }}
        </x-filament::section.heading>
        <x-filament::section.description class="mt-1 max-w-2xl">
            @if ($collection->isAutomatic())
                {{ __('shopper::pages/collections.automatic_description') }}
            @else
                {{ __('shopper::pages/collections.manual_description') }}
            @endif
        </x-filament::section.description>
    </div>

    <div class="mt-6">
        {{ $this->table }}
    </div>
</div>
