<x-shopper::container>
    <x-shopper::heading :title="__('shopper::pages/auth.account.title')" />

    <livewire:shopper-account.profile />

    <x-shopper::separator />

    <livewire:shopper-account.password />

    @if (config('shopper.auth.2fa_enabled'))
        <x-shopper::separator />

        <livewire:shopper-account.two-factor />
    @endif

    <x-shopper::separator />

    <livewire:shopper-account.devices />
</x-shopper::container>
