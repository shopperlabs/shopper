<x-shopper::layouts.base :title="$title ?? null">

    <div class="h-screen flex overflow-hidden" x-data="{ sidebarOpen: false, modalDemo: false }" @keydown.window.escape="sidebarOpen = false">
        <x-shopper::layouts.app.sidebar.setting />

        <x-shopper::layouts.app.sidebar.mobile-setting />

        <div class="flex flex-col w-0 flex-1 overflow-hidden overflow-y-auto">
            <x-shopper::layouts.app.header />

            <main class="flex-1 relative z-0 py-3 lg:py-6">
                <div {{ $attributes->merge(['class' => 'min-h-(screen-content)']) }}>
                    {{ $slot }}
                </div>
            </main>
        </div>

        <x-shopper::wip />
    </div>

</x-shopper::layouts.base>
