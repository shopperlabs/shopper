<x-shopper::container>
    <x-shopper::breadcrumb
        :back="route('shopper.settings.locations')"
        :current="__('shopper::pages/settings/global.location.add')"
    >
        <x-untitledui-chevron-left class="size-4 shrink-0 text-gray-300 dark:text-gray-600" />
        <x-shopper::breadcrumb.link
            :link="route('shopper.settings.locations')"
            :title="__('shopper::pages/settings/global.location.menu')"
        />
    </x-shopper::breadcrumb>

    <x-shopper::heading class="my-6" :title="__('shopper::pages/settings/global.location.add')" />

    <livewire:shopper-settings.locations.form :inventory="new \Shopper\Core\Models\Inventory()" />
</x-shopper::container>
