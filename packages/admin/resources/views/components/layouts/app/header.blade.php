<div class="sticky top-0 z-40 shrink-0 flex h-16 bg-white dark:bg-gray-900 lg:py-4 lg:h-auto border-b border-gray-200 dark:border-gray-700">
    <button @click.stop="sidebarOpen = true" class="px-4 border-r border-gray-200 dark:border-gray-700 text-gray-500 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-800 focus:text-gray-600 dark:focus:text-gray-500 lg:hidden" aria-label="Open sidebar">
        <x-untitledui-menu-03 class="h-6 w-6" />
    </button>
    <div class="flex-1 px-4 lg:px-6 flex items-center justify-between">
        <div class="flex-1 flex">
            @if(config('shopper.components.dashboard.components.search'))
                <livewire:shopper-search />
            @endif
        </div>
        <div class="ml-4 flex items-center space-x-3 lg:ml-6 lg:space-x-4">
            <div
                x-data="{ show: false }"
                @keydown.esc.stop="show = false"
                x-on:shortcut-list.window="show = !show"
                @click.away="show = false"
                class="relative inline-block"
            >
                <button @click="show = !show" type="button" class="inline-flex items-center space-x-2 p-1 text-gray-500 text-sm leading-5 dark:text-gray-300 hover:text-gray-700 dark:hover:text-gray-300 ring-1 ring-gray-200 dark:ring-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800">
                    <x-untitledui-keyboard class="h-6 w-6" stroke-width="1.5" aria-hidden="true" />
                </button>
                <div x-cloak
                     x-show="show"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 -translate-y-2"
                     x-transition:enter-end="transform opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 translate-y-0"
                     x-transition:leave-end="transform opacity-0 -translate-y-2"
                     class="absolute right-0 z-10 mt-2 w-80 origin-top-right rounded-lg bg-white/90 dark:bg-gray-800/90 shadow-md ring-1 ring-black dark:ring-gray-800 ring-opacity-5 focus:outline-none backdrop-blur-md"
                     aria-orientation="vertical"
                     role="menu"
                >
                    <ul class="py-1 divide-y divide-gray-200 dark:divide-gray-700" role="list">
                        @if(config('shopper.components.search'))
                            <x-shopper::shortcut :name="__('shopper::words.global_search')" command="Ctrl" param="K" />
                        @endif
                        <x-shopper::shortcut :name="__('shopper::words.display_shortcuts')" command="Shift" param="F1" />
                        <x-shopper::shortcut :name="__('shopper::words.go_to_documentation')" command="Alt" param="D" />
                    </ul>
                </div>
            </div>
            <a href="{{ url('/') }}" target="_blank" class="hidden lg:inline-flex items-center p-1 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white ring-1 ring-gray-200 dark:ring-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800">
                <x-untitledui-google-chrome class="h-6 w-6" stroke-width="1.5" aria-hidden="true" />
            </a>
            <livewire:shopper-account.dropdown />
        </div>
    </div>
</div>
