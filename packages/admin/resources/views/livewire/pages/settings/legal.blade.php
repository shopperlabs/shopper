<div x-data="{
    options: ['privacy', 'terms', 'shipping', 'refund'],
    currentTab: 'privacy',
}">
    <x-shopper::container>
        <x-shopper::breadcrumb
            :back="route('shopper.settings.index')"
            :current="__('shopper::pages/settings/global.legal.title')"
        >
            <x-untitledui-chevron-left class="h-4 w-4 shrink-0 text-gray-300 dark:text-gray-600" aria-hidden="true" />
            <x-shopper::breadcrumb.link
                :link="route('shopper.settings.index')"
                :title="__('shopper::pages/settings/global.menu')"
            />
        </x-shopper::breadcrumb>
        <x-shopper::heading class="my-6">
            <x-slot name="title">
                {{ __('shopper::pages/settings/global.legal.title') }}
            </x-slot>
        </x-shopper::heading>
    </x-shopper::container>

    <div class="relative border-t border-gray-200 dark:border-gray-700">
        <x-filament::tabs :contained="true">
            <x-filament::tabs.item alpine-active="currentTab === 'privacy'" x-on:click="currentTab = 'privacy'">
                {{ __('shopper::pages/settings/global.legal.privacy') }}
            </x-filament::tabs.item>
            <x-filament::tabs.item alpine-active="currentTab === 'terms'" x-on:click="currentTab = 'terms'">
                {{ __('shopper::pages/settings/global.legal.terms_of_use') }}
            </x-filament::tabs.item>
            <x-filament::tabs.item alpine-active="currentTab === 'shipping'" x-on:click="currentTab = 'shipping'">
                {{ __('shopper::pages/settings/global.legal.shipping') }}
            </x-filament::tabs.item>
            <x-filament::tabs.item alpine-active="currentTab === 'refund'" x-on:click="currentTab = 'refund'">
                {{ __('shopper::pages/settings/global.legal.refund') }}
            </x-filament::tabs.item>
        </x-filament::tabs>
    </div>

    <x-shopper::container class="mt-8">
        <div x-show="currentTab === 'refund'">
            <livewire:shopper-settings.legal.refund :legal="$legals['refund']" />
        </div>
        <div x-cloak x-show="currentTab === 'privacy'">
            <livewire:shopper-settings.legal.privacy :legal="$legals['privacy']" />
        </div>
        <div x-cloak x-show="currentTab === 'terms'">
            <livewire:shopper-settings.legal.terms :legal="$legals['terms-of-use']" />
        </div>
        <div x-cloak x-show="currentTab === 'shipping'">
            <livewire:shopper-settings.legal.shipping :legal="$legals['shipping']" />
        </div>
    </x-shopper::container>

    <x-shopper::learn-more :name="__('shopper::pages/settings/global.legal.title')" link="legal" />
</div>
