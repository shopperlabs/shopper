<div>
    <x-shopper::container>
        <x-shopper::breadcrumb
            :back="route('shopper.settings.index')"
            :current="__('shopper::pages/settings/currencies.title')"
        >
            <x-untitledui-chevron-left class="size-4 shrink-0 text-gray-300 dark:text-gray-600" aria-hidden="true" />
            <x-shopper::breadcrumb.link
                :link="route('shopper.settings.index')"
                :title="__('shopper::pages/settings/global.menu')"
            />
        </x-shopper::breadcrumb>
        <x-shopper::heading class="my-6" :title="__('shopper::pages/settings/currencies.title')" />

        {{ $this->table }}
    </x-shopper::container>

    <x-filament-actions::modals />
</div>
