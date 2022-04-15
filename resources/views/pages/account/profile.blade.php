<x-shopper::layouts.app :title="__('shopper::pages/auth.account.meta_title')">

    <x-shopper::heading>
        <x-slot name="title">
            {{ __('shopper::pages/auth.account.title') }}
        </x-slot>
    </x-shopper::heading>

    <livewire:shopper-account.profile />

    <div class="hidden sm:block">
        <div class="py-5">
            <div class="border-t border-secondary-200 dark:border-secondary-700"></div>
        </div>
    </div>

    <livewire:shopper-account.password />

    @if (config('shopper.auth.2fa_enabled'))
        <div class="hidden sm:block">
            <div class="py-5">
                <div class="border-t border-secondary-200 dark:border-secondary-700"></div>
            </div>
        </div>

        <livewire:shopper-account.two-factor />
    @endif

    <div class="hidden sm:block">
        <div class="py-5">
            <div class="border-t border-secondary-200 dark:border-secondary-700"></div>
        </div>
    </div>

    <livewire:shopper-account.devices />

</x-shopper::layouts.app>
