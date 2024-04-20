<div x-data="{
    options: ['general'],
    currentTab: 'general',
}">
    <x-shopper::container>
        <x-shopper::breadcrumb
            :back="route('shopper.settings.index')"
            :current="__('shopper::pages/settings.payment.title')"
        >
            <x-untitledui-chevron-left class="h-4 w-4 shrink-0 text-gray-300 dark:text-gray-600" aria-hidden="true" />
            <x-shopper::breadcrumb.link
                :link="route('shopper.settings.index')"
                :title="__('shopper::words.settings')"
            />
        </x-shopper::breadcrumb>
        <x-shopper::heading class="my-6">
            <x-slot name="title">
                {{ __('shopper::pages/settings.payment.title') }}
            </x-slot>

            <x-slot name="action">
                <x-shopper::buttons.primary
                    wire:click="$dispatch(
                        'openModal',
                        { component: 'shopper-modals.payment-method-form' }
                    )"
                    type="button"
                >
                    {{ __('shopper::pages/settings.payment.create_payment') }}
                </x-shopper::buttons.primary>
            </x-slot>
        </x-shopper::heading>
    </x-shopper::container>

    <div class="relative space-y-4 border-gray-200 px-4 dark:border-gray-700 lg:border-t lg:px-0">
        <x-filament::tabs :contained="true">
            <x-filament::tabs.item alpine-active="currentTab === 'general'" x-on:click="currentTab = 'general'">
                {{ __('shopper::words.general') }}
            </x-filament::tabs.item>
        </x-filament::tabs>
    </div>

    <x-shopper::container class="mt-6 pb-10">
        <div x-show="currentTab === 'general'">
            {{ $this->table }}
        </div>
    </x-shopper::container>
</div>
