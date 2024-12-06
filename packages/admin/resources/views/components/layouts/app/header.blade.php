<div
    class="sh-header sticky top-0 z-20 flex h-16 shrink-0 border-b border-gray-200 bg-white dark:border-white/10 dark:bg-gray-900 lg:h-auto lg:py-4"
>
    <button
        @click.stop="sidebarOpen = true"
        class="border-r border-gray-200 px-4 text-gray-500 focus:bg-gray-100 focus:text-gray-600 focus:outline-none dark:border-white/10 dark:focus:bg-gray-800 dark:focus:text-gray-500 lg:hidden"
        aria-label="Open sidebar"
    >
        <x-untitledui-menu-03 class="size-6" aria-hidden="true" />
    </button>
    <div class="flex flex-1 items-center justify-between px-4 lg:px-6">
        <div class="flex flex-1">
            @if (config('shopper.components.dashboard.components.search'))
                <livewire:shopper-search />
            @endif
        </div>
        <div class="ml-4 flex items-center space-x-3 lg:ml-6 lg:space-x-4">
            <a
                href="{{ url('/') }}"
                target="_blank"
                class="hidden items-center rounded-lg p-1 text-gray-500 ring-1 ring-gray-200 hover:bg-gray-50 hover:text-gray-700 dark:text-gray-400 dark:ring-white/10 dark:hover:bg-gray-800 dark:hover:text-white lg:inline-flex"
            >
                <x-untitledui-google-chrome class="size-6" stroke-width="1.5" aria-hidden="true" />
            </a>
            <livewire:shopper-account.dropdown />
        </div>
    </div>
</div>
