<x-shopper::layouts.base :title="$title ?? null" class="overflow-hidden bg-gray-50">
    <div
        class="flex h-screen overflow-hidden"
        x-data="{ sidebarOpen: false }"
        @keydown.window.escape="sidebarOpen = false"
    >
        <x-shopper::layouts.app.sidebar />

        <x-shopper::layouts.app.sidebar.mobile />

        <div
            class="flex w-0 flex-1 flex-col overflow-hidden bg-white ring-1 ring-gray-200 dark:bg-gray-900 dark:ring-gray-800 lg:my-2 lg:rounded-bl-2xl lg:rounded-tl-2xl lg:pb-1.5"
        >
            <div class="flex flex-1 flex-col justify-between overflow-hidden overflow-y-auto">
                <x-shopper::layouts.app.header />

                @isset($subHeading)
                    {{ $subHeading }}
                @endisset

                <main class="z-0 flex-1 pt-4 lg:pt-10">
                    <div {{ $attributes->twMerge(['class' => 'flex-1 min-h-full']) }}>
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>
    </div>
</x-shopper::layouts.base>
