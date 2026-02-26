<div class="h-full">
    <!-- Desktop Sidebar -->
    <aside
        class="sh-si hidden h-full lg:flex lg:shrink-0"
        x-bind:class="{ 'sh-si-collapsed': $store.sidebar.isCollapsed }"
    >
        <div
            class="sh-si-content h-full flex-1 overflow-hidden transition-all duration-200"
            x-bind:style="
                $store.sidebar.isCollapsed
                    ? 'width: var(--sidebar-collapsed-width)'
                    : 'width: var(--sidebar-width)'
            "
        >
            <div class="from-primary-600 to-primary-100 dark:to-primary-600/10 h-1 bg-linear-to-br"></div>
            <div class="flex h-full flex-col">
                <!-- Header / Branding -->
                <div class="px-3 pt-3 pb-2">
                    <x-shopper::link
                        :href="route('shopper.dashboard')"
                        class="relative flex items-center gap-2 rounded-md px-2 py-1.5"
                        x-bind:class="$store.sidebar.isCollapsed
                            ? 'justify-center'
                            : 'bg-white shadow-xs ring-1 ring-gray-200 dark:bg-white/5 dark:ring-white/20'"
                    >
                        <x-shopper::brand class="size-6 shrink-0" aria-hidden="true" />
                        <div
                            class="min-w-0 truncate overflow-hidden transition-all duration-200"
                            x-show="!$store.sidebar.isCollapsed"
                            x-transition:enter="transition-opacity delay-100 duration-200"
                            x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100"
                            x-transition:leave="transition-opacity duration-100"
                            x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                        >
                            <h4 class="font-heading truncate text-sm/4 font-medium text-gray-900 dark:text-white">
                                {{ shopper_setting('name') }}
                            </h4>
                        </div>
                    </x-shopper::link>
                </div>

                <x-shopper::layouts.sidebar-content :rendered-sidebar="$renderedSidebar" />
            </div>
        </div>
    </aside>

    <!-- Mobile Sidebar -->
    <div x-cloak x-show="$store.sidebar.isOpen" class="lg:hidden">
        <!-- Backdrop -->
        <div
            x-show="$store.sidebar.isOpen"
            x-transition:enter="transition-opacity duration-300 ease-linear"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity duration-300 ease-linear"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="$store.sidebar.close()"
            class="fixed inset-0 z-40 bg-gray-950/50 backdrop-blur-xs dark:bg-gray-950/75"
        ></div>

        <!-- Sidebar + Close button container -->
        <div class="pointer-events-none fixed inset-0 z-50 flex">
            <!-- Sidebar Panel -->
            <div
                x-cloak
                x-show="$store.sidebar.isOpen"
                x-transition:enter="transform transition duration-200 ease-in-out"
                x-transition:enter-start="-translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transform transition duration-200 ease-in-out"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="-translate-x-full"
                class="pointer-events-auto relative flex w-full max-w-xs flex-col bg-white dark:bg-gray-900"
            >
                <div class="from-primary-600 to-primary-100 dark:to-primary-600/10 h-1 bg-linear-to-br"></div>
                <div class="flex h-full flex-col overflow-hidden">
                    <!-- Header / Branding -->
                    <div class="px-3 py-4">
                        <div
                            class="relative flex items-start gap-3 rounded-lg bg-white py-2 shadow-xs ring-1 ring-gray-200 dark:bg-white/5 dark:ring-white/20"
                        >
                            <x-shopper::link class="shrink-0" :href="route('shopper.dashboard')">
                                <x-shopper::brand class="size-8" aria-hidden="true" />
                                <span class="absolute inset-0"></span>
                            </x-shopper::link>
                            <div class="truncate">
                                <h4 class="font-heading truncate text-sm/4 font-medium text-gray-900 dark:text-white">
                                    {{ shopper_setting('name') }}
                                </h4>
                                <span class="text-sm/4 text-gray-500 dark:text-gray-400">
                                    {{ shopper_setting('email') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <x-shopper::layouts.sidebar-content :rendered-sidebar="$renderedSidebar" />
                </div>
            </div>

            <div class="pointer-events-auto z-10 p-2">
                <button
                    x-show="$store.sidebar.isOpen"
                    @click="$store.sidebar.close()"
                    class="flex size-10 items-center justify-center rounded-full bg-gray-900/50 text-white hover:bg-gray-900/70 focus:outline-none"
                >
                    <span class="sr-only">Close sidebar</span>
                    <x-untitledui-x-close class="size-5" aria-hidden="true" />
                </button>
            </div>
        </div>
    </div>
</div>
