<div
    x-data="{ theme: null }"
    x-init="
        $watch('theme', () => {
            $dispatch('theme-changed', theme)
        })

        theme = localStorage.getItem('theme') || 'system'
    "
    class="fi-theme-switcher grid grid-flow-col gap-x-1 p-0.5 rounded-lg bg-white dark:bg-gray-900 ring-1 ring-gray-200 dark:ring-white/10"
>
    <x-shopper::theme-switcher.button icon="phosphor-sun" theme="light" />

    <x-shopper::theme-switcher.button icon="phosphor-moon-stars" theme="dark" />

    <x-shopper::theme-switcher.button icon="phosphor-monitor" theme="system" />
</div>
