<div class="h-full">
    <!-- Desktop Sidebar -->
    <aside
        class="sh-si bg-sh-sidebar border-sh-border hidden h-full border-r lg:flex lg:shrink-0"
        x-bind:class="{ 'sh-si-collapsed': $store.sidebar.isCollapsed }"
    >
        <div class="sh-si-content h-full flex-1 overflow-hidden transition-[width] duration-200">
            <div class="flex h-full flex-col">
                <x-shopper::layouts.sidebar-content :rendered-sidebar="$renderedSidebar" />
            </div>
        </div>
    </aside>

    <!-- Mobile Sidebar -->
    <div x-cloak x-show="$store.sidebar.isOpen" class="lg:hidden">
        <!-- Backdrop -->
        <div
            x-show="$store.sidebar.isOpen"
            x-transition:enter="transition-opacity duration-300 ease-linear"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity duration-300 ease-linear"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="$store.sidebar.close()"
            class="fixed inset-0 z-40 bg-gray-950/50 backdrop-blur-xs/75"
        ></div>

        <!-- Sidebar + Close button container -->
        <div class="pointer-events-none fixed inset-0 z-50 flex">
            <!-- Sidebar Panel -->
            <div
                x-cloak
                x-show="$store.sidebar.isOpen"
                x-transition:enter="transform transition duration-200 ease-in-out"
                x-transition:enter-start="-translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transform transition duration-200 ease-in-out"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="-translate-x-full"
                class="bg-sh-sidebar pointer-events-auto relative flex w-full max-w-xs flex-col"
            >
                <div class="flex h-full flex-col overflow-hidden">
                    <x-shopper::layouts.sidebar-content :rendered-sidebar="$renderedSidebar" />
                </div>
            </div>

            <div class="pointer-events-auto z-10 p-2">
                <button
                    x-show="$store.sidebar.isOpen"
                    @click="$store.sidebar.close()"
                    class="flex size-10 items-center justify-center rounded-full bg-gray-900/50 text-white hover:bg-gray-900/70 focus:outline-none"
                >
                    <span class="sr-only">Close sidebar</span>
                    <x-untitledui-x-close class="size-5" aria-hidden="true" />
                </button>
            </div>
        </div>
    </div>
</div>
