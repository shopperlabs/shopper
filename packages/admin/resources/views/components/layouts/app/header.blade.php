<div class="sticky top-0 z-10 shrink-0 flex h-16 bg-white dark:bg-secondary-900 lg:bg-transparent lg:dark:bg-transparent backdrop-filter backdrop-blur-md shadow lg:shadow-none lg:py-4 lg:h-auto border-b border-transparent lg:border-secondary-200 dark:border-secondary-700">
    <button @click.stop="sidebarOpen = true" class="px-4 border-r border-secondary-200 dark:border-secondary-700 text-secondary-500 focus:outline-none focus:bg-secondary-100 dark:focus:bg-secondary-800 focus:text-secondary-600 dark:focus:text-secondary-500 lg:hidden" aria-label="Open sidebar">
        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
        </svg>
    </button>
    <div class="flex-1 px-4 lg:px-6 flex justify-between">
        <div class="flex-1 flex">
            <livewire:shopper-search />
        </div>
        <div class="ml-4 flex items-center lg:ml-6 divide-x divide-secondary-200 dark:divide-secondary-700">
            <div class="flex items-center space-x-4 pr-4">
                <div
                    x-data="{ show: false }"
                    @keydown.esc.stop="show = false"
                    x-on:shortcut-list.window="show = !show"
                    @click.away="show = false"
                    class="relative inline-block"
                >
                    <button @click="show = !show" type="button" class="inline-flex items-center px-1.5 py-1 text-secondary-500 text-sm leading-5 dark:text-secondary-300 hover:text-secondary-700 dark:hover:text-secondary-300 font-bold ring-1 ring-secondary-200 dark:ring-secondary-700 rounded-md hover:bg-secondary-50 dark:hover:bg-secondary-800">
                        <span class="sr-only">{{ __('shopper::words.view_shortcuts') }}</span>
                        <svg class="h-5 w-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" d="M6 10h.01M8 14h.01M10 10h.01M12 14h.01M14 10h.01M16 14h.01M18 10h.01M5.2 18h13.6c1.12 0 1.68 0 2.108-.218a2 2 0 0 0 .874-.874C22 16.48 22 15.92 22 14.8V9.2c0-1.12 0-1.68-.218-2.108a2 2 0 0 0-.874-.874C20.48 6 19.92 6 18.8 6H5.2c-1.12 0-1.68 0-2.108.218a2 2 0 0 0-.874.874C2 7.52 2 8.08 2 9.2v5.6c0 1.12 0 1.68.218 2.108a2 2 0 0 0 .874.874C3.52 18 4.08 18 5.2 18Z"/>
                        </svg>
                        <kbd class="text-xxs text-secondary-700 dark:text-secondary-300">Shift+F1</kbd>
                    </button>
                    <div x-cloak
                         x-show="show"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 -translate-y-2"
                         x-transition:enter-end="transform opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 translate-y-0"
                         x-transition:leave-end="transform opacity-0 -translate-y-2"
                         class="absolute right-0 z-10 mt-2 w-80 origin-top-right rounded-lg bg-white/80 dark:bg-secondary-800/80 shadow-md ring-1 ring-black dark:ring-secondary-800 ring-opacity-5 focus:outline-none backdrop-blur-lg"
                         aria-orientation="vertical"
                         role="menu"
                    >
                        <ul class="py-1 divide-y divide-secondary-200 dark:divide-secondary-700" role="list">
                            <x-shopper::shortcut :name="__('shopper::words.global_search')" command="Ctrl" param="K" />
                            <x-shopper::shortcut :name="__('shopper::words.display_shortcuts')" command="Shift" param="F1" />
                            <x-shopper::shortcut :name="__('shopper::words.go_to_documentation')" command="Alt" param="D" />
                        </ul>
                    </div>
                </div>
            </div>
            <a href="{{ url('/') }}" target="_blank" class="pl-4 inline-flex items-center text-secondary-500 hover:text-secondary-700 dark:text-secondary-400 dark:hover:text-white">
                <svg class="h-5 w-5 mr-1.5" stroke="currentColor" fill="none" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path  stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" d="M12 8a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm0 0h9.17M3.95 6.06 8.54 14m2.34 7.94L15.46 14M22 12c0 5.523-4.477 10-10 10S2 17.523 2 12 6.477 2 12 2s10 4.477 10 10Z"/>
                </svg>
                {{ __('shopper::layout.view_site') }}
            </a>
        </div>
    </div>
</div>
