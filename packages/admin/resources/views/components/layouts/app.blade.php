<x-shopper::layouts.base :title="$title ?? null" class="overflow-hidden">

    <div class="h-screen flex overflow-hidden" x-data="{ sidebarOpen: false, modalDemo: false }" @keydown.window.escape="sidebarOpen = false">
        <x-shopper::layouts.app.sidebar />

        <x-shopper::layouts.app.sidebar.mobile />

        <div class="flex flex-col w-0 flex-1 overflow-hidden overflow-y-auto scrolling">
            <x-shopper::layouts.app.header />

            @isset($subHeading)
                {{ $subHeading }}
            @endisset

            <main class="flex-1 z-0 py-4 lg:py-6">
                <div {{ $attributes->merge(['class' => 'min-h-(screen-content)']) }}>
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

</x-shopper::layouts.base>
