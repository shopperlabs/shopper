<div x-cloak x-show="sidebarOpen" class="lg:hidden">
    <div
        x-cloak
        x-show="sidebarOpen"
        @click="sidebarOpen = false"
        @click.away="sidebarOpen = false"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 transition-opacity duration-300 ease-linear"
    >
        <div class="absolute inset-0 bg-gray-900 opacity-75 backdrop-blur-md"></div>
    </div>

    <div class="fixed inset-0 z-50 flex">
        <div
            x-cloak
            x-show="sidebarOpen"
            x-transition:enter-start="-translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
            class="flex w-full max-w-xs flex-1 transform bg-white duration-200 ease-in-out dark:bg-gray-900"
        >
            <div class="absolute right-0 top-0 -mr-14 p-1">
                <button
                    x-show="sidebarOpen"
                    @click="sidebarOpen = false"
                    class="flex size-12 items-center justify-center rounded-full focus:bg-gray-500 focus:outline-none"
                >
                    <x-untitledui-x-close class="size-6 text-white" aria-hidden="true" />
                </button>
            </div>

            <x-shopper::layouts.app.sidebar.content class="w-full" />
        </div>

        <div class="w-14 shrink-0"></div>
    </div>
</div>
