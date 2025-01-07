<x-shopper::container class="py-5 space-y-5">
    <x-shopper::breadcrumb :back="route('shopper.dashboard')">
        <x-untitledui-chevron-left class="size-4 text-gray-300 dark:text-gray-600" aria-hidden="true" />
        <span class="truncate text-gray-500 dark:text-gray-400">
            {{ __('shopper::pages/auth.account.title') }}
        </span>
    </x-shopper::breadcrumb>

    <x-shopper::heading :title="__('shopper::pages/auth.account.title')" class="border-b border-gray-200 dark:border-white/10 pb-5" />

    <div>
        <livewire:shopper-account.profile />

        <x-shopper::separator />

        <livewire:shopper-account.password />

        @if (config('shopper.auth.2fa_enabled'))
            <x-shopper::separator />

            <livewire:shopper-account.two-factor />
        @endif

        <x-shopper::separator />

        <livewire:shopper-account.devices />
    </div>
</x-shopper::container>
