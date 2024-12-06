<x-shopper::layouts.app :title="$title ?? null">
    <x-slot name="subHeading">
        <div
            class="sticky top-16 z-10 border-b border-t border-gray-200 bg-white/80 backdrop-blur-md backdrop-filter dark:border-white/10 dark:bg-gray-800/80 lg:top-[3.8rem] lg:border-t-0"
        >
            <div
                x-data="{
                    displayLeftArrow: false,
                    displayRightArrow: true,
                    element: document.getElementById('setting-tabs'),
                    currentTab: document
                        .getElementById('setting-tabs')
                        .querySelector('.current'),

                    slideLeft() {
                        this.element.scrollLeft -= 100
                        this.onScroll()
                    },
                    slideRight() {
                        this.element.scrollLeft += 100
                        this.onScroll()
                    },
                    onScroll() {
                        this.displayLeftArrow = this.element.scrollLeft >= 20
                        let maxScrollPosition =
                            this.element.scrollWidth - this.element.clientWidth - 20
                        this.displayRightArrow = this.element.scrollLeft <= maxScrollPosition
                    },
                    scrollToActive() {
                        if (this.currentTab) {
                            this.element.scrollLeft = this.currentTab.offsetLeft - 50
                        }
                    },
                }"
                x-init="scrollToActive()"
                class="relative overflow-hidden"
            >
                <div
                    x-cloak
                    x-show="displayLeftArrow"
                    x-transition:enter="transition duration-300 ease-out"
                    x-transition:enter-start="-translate-x-2 opacity-0"
                    x-transition:enter-end="translate-x-0 opacity-100"
                    x-transition:leave="transition duration-300 ease-in"
                    x-transition:leave-start="translate-x-0 opacity-100"
                    x-transition:leave-end="-translate-x-2 opacity-0"
                    class="absolute top-0 flex h-full w-24 items-center bg-gradient-to-r from-white px-2.5 dark:from-gray-900"
                >
                    <button
                        @click="slideLeft()"
                        type="button"
                        class="flex size-8 items-center justify-center rounded-full text-gray-400 transition duration-200 ease-in-out hover:bg-gray-50 focus:outline-none dark:bg-gray-800 dark:text-gray-500"
                    >
                        <x-untitledui-chevron-left class="size-6" aria-hidden="true" />
                    </button>
                </div>
                <nav
                    @scroll="onScroll()"
                    class="hide-scroll -mb-px flex space-x-8 overflow-x-auto scroll-smooth pl-6 pr-10"
                    aria-label="Tabs"
                    id="setting-tabs"
                >
                    @foreach (config('shopper.settings.items', []) as $menu)
                        <x-shopper::menu.nav-setting :menu="$menu" />
                    @endforeach
                </nav>
                <div
                    x-show="displayRightArrow"
                    x-transition:enter="transition duration-300 ease-out"
                    x-transition:enter-start="translate-x-2 opacity-0"
                    x-transition:enter-end="translate-x-0 opacity-100"
                    x-transition:leave="transition duration-300 ease-in"
                    x-transition:leave-start="translate-x-0 opacity-100"
                    x-transition:leave-end="translate-x-2 opacity-0"
                    class="absolute right-0 top-0 flex h-full w-24 items-center justify-end bg-gradient-to-l from-white px-2.5 dark:from-gray-900"
                >
                    <button
                        @click="slideRight()"
                        type="button"
                        class="flex size-8 items-center justify-center rounded-full text-gray-400 transition duration-200 ease-in-out hover:bg-gray-50 focus:outline-none dark:bg-gray-800 dark:text-gray-500"
                    >
                        <x-untitledui-chevron-right class="size-6" aria-hidden="true" />
                    </button>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-5">
        {{ $slot }}
    </div>
</x-shopper::layouts.app>
