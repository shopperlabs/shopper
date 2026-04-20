<x-shopper::container>
    <x-shopper::heading :title="__('shopper::pages/settings/global.location.add')" />

    <livewire:shopper-settings.locations.form :inventory="new \Shopper\Core\Models\Inventory()" />
</x-shopper::container>
