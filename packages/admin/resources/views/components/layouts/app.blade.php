@props([
    'title' => null,
])

<x-shopper::layouts.base :$title>
    <div
        class="flex h-screen flex-col overflow-hidden"
        x-data
        @keydown.window.escape="$store.sidebar.close()"
    >
        {{ shopper()->getRenderHook(\Shopper\View\LayoutRenderHook::BEFORE_HEADER) }}

        <x-shopper::layouts.header />

        {{ shopper()->getRenderHook(\Shopper\View\LayoutRenderHook::AFTER_HEADER) }}

        <div class="flex flex-1 overflow-hidden">
            @persist('sidebar')
                @livewire(
                    'sidebar',
                    [
                        'sidebarClass' => \Shopper\Sidebar\AdminSidebar::class,
                        'view' => 'shopper::livewire.sidebar',
                    ]
                )
            @endpersist

            <div class="sh-main flex w-0 flex-1 flex-col overflow-hidden">
                @isset($subHeading)
                    {{ $subHeading }}
                @endisset

                <main class="sh-main-content relative flex-1 overflow-y-auto">
                    {{ shopper()->getRenderHook(\Shopper\View\LayoutRenderHook::CONTENT_START) }}

                    <div {{ $attributes->twMerge(['class' => 'flex-1 min-h-full']) }}>
                        {{ $slot }}
                    </div>

                    {{ shopper()->getRenderHook(\Shopper\View\LayoutRenderHook::CONTENT_END) }}
                </main>
            </div>

            {{ shopper()->getRenderHook(\Shopper\View\LayoutRenderHook::CONTENT_ASIDE) }}
        </div>

        <livewire:shopper-session-expired />
    </div>
</x-shopper::layouts.base>
