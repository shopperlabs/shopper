<!-- Navigation -->
<div class="flex min-h-0 flex-1 flex-col justify-between">
    {{ shopper()->getRenderHook(\Shopper\View\LayoutRenderHook::SIDEBAR_START) }}

    <div class="relative min-h-0 flex-1">
        <!-- Top fade gradient -->
        <div
            class="from-sh-sidebar pointer-events-none absolute top-0 right-0.5 left-0 z-10 h-6 bg-linear-to-b to-transparent"
        ></div>

        <div class="h-full overflow-y-auto">
            <nav class="sh-si-nav px-3 py-3">
                {!! $renderedSidebar !!}
            </nav>
        </div>

        <!-- Bottom fade gradient -->
        <div
            class="from-sh-sidebar pointer-events-none absolute right-0.5 bottom-0 left-0 z-10 h-6 bg-linear-to-t to-transparent"
        ></div>
    </div>

    {{ shopper()->getRenderHook(\Shopper\View\LayoutRenderHook::SIDEBAR_END) }}
</div>
