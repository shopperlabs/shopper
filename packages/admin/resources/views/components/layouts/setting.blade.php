<x-shopper::layouts.app :title="$title ?? null">
    <x-slot name="subHeading">
        <div
            class="sticky top-16 z-10 border-t border-b border-gray-200 bg-white backdrop-blur-md backdrop-filter lg:top-[3.8rem] lg:border-t-0 dark:border-white/10 dark:bg-gray-900/80"
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
                    class="absolute top-0 flex h-full w-24 items-center bg-linear-to-r from-white px-2.5 dark:from-gray-900"
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
                    class="hide-scroll -mb-px flex space-x-8 overflow-x-auto scroll-smooth pr-10 pl-6"
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
                    class="absolute top-0 right-0 flex h-full w-24 items-center justify-end bg-linear-to-l from-white px-2.5 dark:from-gray-900"
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
