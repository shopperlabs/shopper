<div x-cloak x-show="sidebarOpen" class="lg:hidden">
    <div x-cloak
         x-show="sidebarOpen"
         @click="sidebarOpen = false"
         @click.away="sidebarOpen = false"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 transition-opacity ease-linear duration-300"
    >
        <div class="absolute inset-0 bg-gray-900 backdrop-blur-md opacity-75"></div>
    </div>

    <div class="fixed inset-0 flex z-50">
        <div x-cloak
             x-show="sidebarOpen"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full"
             class="flex-1 flex max-w-xs w-full bg-white dark:bg-gray-900 transform ease-in-out duration-200"
        >
            <div class="absolute top-0 right-0 -mr-14 p-1">
                <button x-show="sidebarOpen" @click="sidebarOpen = false" class="flex items-center justify-center h-12 w-12 rounded-full focus:outline-none focus:bg-gray-500">
                    <x-untitledui-x-close class="h-6 w-6 text-white" />
                </button>
            </div>

            <x-shopper::layouts.app.sidebar.content class="w-full" />
        </div>

        <div class="shrink-0 w-14"></div>
    </div>
</div>
