<x-shopper::container class="space-y-5 py-5">
    <x-shopper::heading
        :title="__('shopper::pages/auth.account.title')"
        class="border-b border-gray-200 pb-5 dark:border-white/10"
    />

    {{ shopper()->getRenderHook(\Shopper\View\LayoutRenderHook::ACCOUNT_START) }}

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

    {{ shopper()->getRenderHook(\Shopper\View\LayoutRenderHook::ACCOUNT_END) }}
</x-shopper::container>
