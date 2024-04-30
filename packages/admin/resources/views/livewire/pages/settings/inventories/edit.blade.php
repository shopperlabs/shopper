<x-shopper::container>
    <x-shopper::breadcrumb :back="route('shopper.settings.inventories')" :current="$inventory->name">
        <x-untitledui-chevron-left class="h-4 w-4 shrink-0 text-gray-300 dark:text-gray-600" />
        <x-shopper::breadcrumb.link
            :link="route('shopper.settings.inventories')"
            :title="__('shopper::pages/settings/global.location.menu')"
        />
    </x-shopper::breadcrumb>

    <x-shopper::heading class="my-6">
        <x-slot name="title">
            {{ $inventory->name }}
        </x-slot>
    </x-shopper::heading>

    <livewire:shopper-settings.inventories.form :inventory="$inventory" />
</x-shopper::container>
