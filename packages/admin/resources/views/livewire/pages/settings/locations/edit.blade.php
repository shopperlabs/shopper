<x-shopper::container>
    <x-shopper::breadcrumb :back="route('shopper.settings.locations')" :current="$inventory->name">
        <x-untitledui-chevron-left class="size-4 shrink-0 text-gray-300 dark:text-gray-600" />
        <x-shopper::breadcrumb.link
            :link="route('shopper.settings.locations')"
            :title="__('shopper::pages/settings/global.location.menu')"
        />
    </x-shopper::breadcrumb>

    <x-shopper::heading class="my-6" :title="$inventory->name" />

    <livewire:shopper-settings.locations.form :$inventory />
</x-shopper::container>
