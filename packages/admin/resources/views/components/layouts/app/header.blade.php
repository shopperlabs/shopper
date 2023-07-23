<div class="sticky top-0 z-20 shrink-0 flex h-16 bg-white dark:bg-secondary-900 lg:bg-transparent lg:dark:bg-transparent backdrop-blur-md shadow lg:shadow-none lg:py-4 lg:h-auto border-b border-transparent lg:border-secondary-200 dark:border-secondary-700">
    <button @click.stop="sidebarOpen = true" class="px-4 border-r border-secondary-200 dark:border-secondary-700 text-secondary-500 focus:outline-none focus:bg-secondary-100 dark:focus:bg-secondary-800 focus:text-secondary-600 dark:focus:text-secondary-500 lg:hidden" aria-label="Open sidebar">
        <x-untitledui-menu-03 class="h-6 w-6" />
    </button>
    <div class="flex-1 px-4 lg:px-6 flex items-center justify-between">
        <div class="flex-1 flex">
            <livewire:shopper-search />
        </div>
        <div class="ml-4 flex items-center lg:ml-6 divide-x divide-secondary-200 dark:divide-secondary-700">
            <div class="flex items-center space-x-4 lg:pr-4">
                <div
                    x-data="{ show: false }"
                    @keydown.esc.stop="show = false"
                    x-on:shortcut-list.window="show = !show"
                    @click.away="show = false"
                    class="relative inline-block"
                >
                    <button @click="show = !show" type="button" class="inline-flex items-center space-x-2 px-1.5 py-1 text-secondary-500 text-sm leading-5 dark:text-secondary-300 hover:text-secondary-700 dark:hover:text-secondary-300 font-bold ring-1 ring-secondary-200 dark:ring-secondary-700 rounded-md hover:bg-secondary-50 dark:hover:bg-secondary-800">
                        <x-untitledui-keyboard class="h-5 w-5" stroke-width="1.5" />
                        <kbd class="hidden text-xxs text-secondary-700 dark:text-secondary-300 lg:block">Shift+F1</kbd>
                    </button>
                    <div x-cloak
                         x-show="show"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 -translate-y-2"
                         x-transition:enter-end="transform opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 translate-y-0"
                         x-transition:leave-end="transform opacity-0 -translate-y-2"
                         class="absolute right-0 z-10 mt-2 w-80 origin-top-right rounded-lg bg-white/90 dark:bg-secondary-800/90 shadow-md ring-1 ring-black dark:ring-secondary-800 ring-opacity-5 focus:outline-none backdrop-blur-md"
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
            <a href="{{ url('/') }}" target="_blank" class="hidden pl-4 lg:inline-flex items-center text-secondary-500 hover:text-secondary-700 dark:text-secondary-400 dark:hover:text-white">
                <x-untitledui-google-chrome class="h-5 w-5 mr-2" />
                {{ __('shopper::layout.view_site') }}
            </a>
        </div>
    </div>
</div>
