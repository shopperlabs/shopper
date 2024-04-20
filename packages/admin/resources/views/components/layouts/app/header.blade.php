<div
    class="sticky top-0 z-20 flex h-16 shrink-0 border-b border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-900 lg:h-auto lg:py-4"
>
    <button
        @click.stop="sidebarOpen = true"
        class="border-r border-gray-200 px-4 text-gray-500 focus:bg-gray-100 focus:text-gray-600 focus:outline-none dark:border-gray-700 dark:focus:bg-gray-800 dark:focus:text-gray-500 lg:hidden"
        aria-label="Open sidebar"
    >
        <x-untitledui-menu-03 class="h-6 w-6" aria-hidden="true" />
    </button>
    <div class="flex flex-1 items-center justify-between px-4 lg:px-6">
        <div class="flex flex-1">
            @if (config('shopper.components.dashboard.components.search'))
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
                <button
                    @click="show = !show"
                    type="button"
                    class="inline-flex items-center space-x-2 rounded-lg p-1 text-sm leading-5 text-gray-500 ring-1 ring-gray-200 hover:bg-gray-50 hover:text-gray-700 dark:text-gray-300 dark:ring-gray-700 dark:hover:bg-gray-800 dark:hover:text-gray-300"
                >
                    <x-untitledui-keyboard class="h-6 w-6" stroke-width="1.5" aria-hidden="true" />
                </button>
                <div
                    x-cloak
                    x-show="show"
                    x-transition:enter="transition duration-100 ease-out"
                    x-transition:enter-start="-translate-y-2 transform opacity-0"
                    x-transition:enter-end="translate-y-0 transform opacity-100"
                    x-transition:leave="transition duration-75 ease-in"
                    x-transition:leave-start="translate-y-0 transform opacity-100"
                    x-transition:leave-end="-translate-y-2 transform opacity-0"
                    class="absolute right-0 z-10 mt-2 w-80 origin-top-right rounded-lg bg-white/90 shadow-md ring-1 ring-black ring-opacity-5 backdrop-blur-md focus:outline-none dark:bg-gray-800/90 dark:ring-gray-800"
                    aria-orientation="vertical"
                    role="menu"
                >
                    <ul class="divide-y divide-gray-200 py-1 dark:divide-gray-700" role="list">
                        @if (config('shopper.components.search'))
                            <x-shopper::shortcut :name="__('shopper::words.global_search')" command="Ctrl" param="K" />
                        @endif

                        <x-shopper::shortcut
                            :name="__('shopper::words.display_shortcuts')"
                            command="Shift"
                            param="F1"
                        />
                        <x-shopper::shortcut
                            :name="__('shopper::words.go_to_documentation')"
                            command="Alt"
                            param="D"
                        />
                    </ul>
                </div>
            </div>
            <a
                href="{{ url('/') }}"
                target="_blank"
                class="hidden items-center rounded-lg p-1 text-gray-500 ring-1 ring-gray-200 hover:bg-gray-50 hover:text-gray-700 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-gray-800 dark:hover:text-white lg:inline-flex"
            >
                <x-untitledui-google-chrome class="h-6 w-6" stroke-width="1.5" aria-hidden="true" />
            </a>
            <livewire:shopper-account.dropdown />
        </div>
    </div>
</div>
