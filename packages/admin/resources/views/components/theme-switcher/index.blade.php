<div
    x-data="{ theme: null }"
    x-init="
        $watch('theme', () => {
            $dispatch('theme-changed', theme)
        })

        theme = localStorage.getItem('theme') || 'system'
    "
    class="fi-theme-switcher grid grid-flow-col gap-x-1"
>
    <x-shopper::theme-switcher.button icon="phosphor-sun" theme="light" />

    <x-shopper::theme-switcher.button icon="phosphor-moon-stars" theme="dark" />

    <x-shopper::theme-switcher.button icon="phosphor-monitor" theme="system" />
</div>
