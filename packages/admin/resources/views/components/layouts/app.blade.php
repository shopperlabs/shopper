<x-shopper::layouts.base :title="$title ?? null" class="overflow-hidden bg-gray-50">
    <div class="h-screen flex overflow-hidden" x-data="{ sidebarOpen: false, modalDemo: false }" @keydown.window.escape="sidebarOpen = false">
        <x-shopper::layouts.app.sidebar />

        <x-shopper::layouts.app.sidebar.mobile />

        <div class="flex flex-1 flex-col w-0 lg:pb-1.5 lg:my-2 overflow-hidden bg-white ring-1 ring-gray-200 dark:bg-gray-900 dark:ring-gray-800 lg:rounded-tl-2xl lg:rounded-bl-2xl">
            <div class="flex flex-1 flex-col justify-between overflow-hidden overflow-y-auto scrolling">
                <x-shopper::layouts.app.header />

                @isset($subHeading)
                    {{ $subHeading }}
                @endisset

                <main class="flex-1 z-0 pt-4 lg:pt-10">
                    <div {{ $attributes->twMerge(['class' => 'flex-1 min-h-full']) }}>
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>
    </div>
</x-shopper::layouts.base>
