<div x-show="sidebarOpen" class="md:hidden">
    <div @click="sidebarOpen = false" x-show="sidebarOpen" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-30 transition-opacity ease-linear duration-300">
        <div class="absolute inset-0 bg-gray-600 opacity-75"></div>
    </div>
    <div class="fixed inset-0 flex z-40">
        <div x-show="sidebarOpen" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="flex-1 flex max-w-xs w-full bg-white transform ease-in-out duration-300">
            <div class="absolute top-0 right-0 -mr-14 p-1">
                <button x-show="sidebarOpen" @click="sidebarOpen = false" class="flex items-center justify-center h-12 w-12 rounded-full focus:outline-none focus:bg-gray-600">
                    <svg class="h-6 w-6 text-white" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            @include('shopper::partials.default.aside._primary')
            @include('shopper::partials.default.aside._secondary')
        </div>
        <div class="flex-shrink-0 w-14">
            <!-- Force sidebar to shrink to fit close icon -->
        </div>
    </div>
</div>
