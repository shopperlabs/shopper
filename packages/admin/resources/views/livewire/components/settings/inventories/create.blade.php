<x-shopper::container>
    <x-shopper::breadcrumb :back="route('shopper.settings.inventories')" :current="__('shopper::pages/settings.location.add')">
        <x-untitledui-chevron-left class="shrink-0 h-4 w-4 text-gray-300 dark:text-gray-600" />
        <x-shopper::breadcrumb.link :link="route('shopper.settings.inventories')" :title="__('shopper::words.locations')" />
    </x-shopper::breadcrumb>

    <x-shopper::heading class="mt-5">
        <x-slot name="title">
            {{ __('shopper::pages/settings.location.add') }}
        </x-slot>
    </x-shopper::heading>

    <livewire:shopper-settings.inventories.form :inventory="new \Shopper\Core\Models\Inventory" />
</x-shopper::container>
