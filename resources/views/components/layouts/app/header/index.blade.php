<div class="sticky top-0 z-10 shrink-0 flex h-16 bg-white dark:bg-secondary-900 md:bg-transparent md:dark:bg-transparent backdrop-filter backdrop-blur-md shadow md:shadow-none md:py-4 md:h-auto border-b border-transparent md:border-secondary-200 dark:border-secondary-700">
    <button @click.stop="sidebarOpen = true" class="px-4 border-r border-secondary-200 dark:border-secondary-700 text-secondary-500 focus:outline-none focus:bg-secondary-100 dark:focus:bg-secondary-800 focus:text-secondary-600 dark:focus:text-secondary-500 md:hidden" aria-label="Open sidebar">
        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
        </svg>
    </button>
    <div class="flex-1 px-4 sm:px-6 md:px-8 flex justify-between">
        <div class="flex-1 flex">
            {{-- Search Component --}}
        </div>
        <div class="ml-4 flex items-center md:ml-6 space-x-4">
            <a x-tooltip.raw="{{ __('View Site') }}" href="{{ url('/') }}" target="_blank" class="text-secondary-500 hover:text-secondary-900 dark:text-secondary-400 dark:hover:text-white">
                <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
            </a>
            <!-- Profile dropdown -->
            <livewire:shopper-account.dropdown />
        </div>
    </div>
</div>
