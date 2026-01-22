<x-shopper::layouts.base :title="$title ?? null">
    <div class="flex h-screen overflow-hidden" x-data @keydown.window.escape="$store.sidebar.close()">
        @persist('sidebar')
            @livewire(
                'sidebar',
                [
                    'sidebarClass' => \Shopper\Sidebar\AdminSidebar::class,
                    'view' => 'shopper::livewire.sidebar',
                ]
            )
        @endpersist

        <div
            class="flex w-0 flex-1 flex-col overflow-hidden bg-white ring-1 ring-gray-200 lg:my-2 lg:rounded-tl-xl lg:rounded-bl-xl dark:bg-gray-900 dark:ring-white/20"
        >
            <div class="flex flex-1 flex-col justify-between overflow-hidden overflow-y-auto">
                <x-shopper::layouts.header />

                @isset($subHeading)
                    {{ $subHeading }}
                @endisset

                <main class="sh-main flex-1">
                    <div {{ $attributes->twMerge(['class' => 'flex-1 min-h-full']) }}>
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>
    </div>
</x-shopper::layouts.base>
