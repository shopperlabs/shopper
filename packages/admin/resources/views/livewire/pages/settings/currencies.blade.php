<div>
    <x-shopper::container>
        <x-shopper::heading :title="__('shopper::pages/settings/currencies.title')" />

        {{ $this->table }}
    </x-shopper::container>

    <x-filament-actions::modals />
</div>
