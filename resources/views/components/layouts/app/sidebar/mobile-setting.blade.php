<div x-cloak x-show="sidebarOpen" class="md:hidden">
    <div x-cloak
         x-show="sidebarOpen"
         @click="sidebarOpen = false"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-40 transition-opacity ease-linear duration-300"
    >
        <div class="absolute inset-0 bg-secondary-900 opacity-75"></div>
    </div>

    <div class="fixed inset-0 flex z-40">
        <div x-cloak
             x-show="sidebarOpen"
             x-transition:enter-start="-translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="-translate-x-full"
             class="flex-1 flex max-w-md w-full bg-white dark:bg-secondary-900 transform ease-in-out duration-200"
        >
            <div class="absolute top-0 right-0 -mr-14 p-1">
                <button x-show="sidebarOpen" @click="sidebarOpen = false" class="flex items-center justify-center h-12 w-12 rounded-full focus:outline-none focus:bg-secondary-600">
                    <svg class="h-6 w-6 text-white" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <x-shopper::layouts.app.sidebar.primary />

            <x-shopper::layouts.app.sidebar.navigation-setting />
        </div>

        <div class="shrink-0 w-14"></div>
    </div>
</div>
