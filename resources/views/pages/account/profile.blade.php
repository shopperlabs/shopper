<x-shopper::layouts.app :title="__('shopper::pages/auth.account.meta_title')">

    <x-shopper::heading>
        <x-slot name="title">
            {{ __('shopper::pages/auth.account.title') }}
        </x-slot>
    </x-shopper::heading>

    <livewire:shopper-account.profile />

    <x-shopper::separator />

    <livewire:shopper-account.password />

    @if (config('shopper.auth.2fa_enabled'))
        <x-shopper::separator />

        <livewire:shopper-account.two-factor />
    @endif

    <x-shopper::separator />

    <livewire:shopper-account.devices />

</x-shopper::layouts.app>
