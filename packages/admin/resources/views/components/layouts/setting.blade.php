<x-shopper::layouts.app :title="$title ?? null">
    <x-slot name="subHeading">
        <div class="sticky top-16 lg:top-[3.8rem] z-10 border-t border-b border-gray-200 lg:border-t-0 dark:border-gray-700 bg-white/80 dark:bg-gray-800/80 backdrop-filter backdrop-blur-md">
            <div x-data="{
                    displayLeftArrow: false,
                    displayRightArrow: true,
                    element: document.getElementById('setting-tabs'),
                    currentTab: document.getElementById('setting-tabs').querySelector('.current'),

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
                        let maxScrollPosition = this.element.scrollWidth - this.element.clientWidth - 20
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
                <div x-cloak x-show="displayLeftArrow"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 -translate-x-2"
                     x-transition:enter-end="opacity-100 translate-x-0"
                     x-transition:leave="transition ease-in duration-300"
                     x-transition:leave-start="opacity-100 translate-x-0"
                     x-transition:leave-end="opacity-0 -translate-x-2"
                     class="flex items-center absolute h-full w-24 top-0 px-2.5 bg-gradient-to-r from-white dark:from-gray-900"
                >
                    <button @click="slideLeft()" type="button" class="flex items-center justify-center text-gray-400 w-8 h-8 focus:outline-none rounded-full dark:text-gray-500 hover:bg-gray-50 dark:bg-gray-800 transition duration-200 ease-in-out">
                        <x-untitledui-chevron-left class="w-6 h-6" aria-hidden="true" />
                    </button>
                </div>
                <nav @scroll="onScroll()" class="-mb-px flex space-x-8 pl-6 pr-10 overflow-x-auto hide-scroll scroll-smooth" aria-label="Tabs" id="setting-tabs">
                    @foreach(config('shopper.settings.items') as $menu)
                        <x-shopper::menu.nav-setting :menu="$menu" />
                    @endforeach
                </nav>
                <div x-show="displayRightArrow"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-x-2"
                     x-transition:enter-end="opacity-100 translate-x-0"
                     x-transition:leave="transition ease-in duration-300"
                     x-transition:leave-start="opacity-100 translate-x-0"
                     x-transition:leave-end="opacity-0 translate-x-2"
                     class="flex items-center justify-end absolute h-full w-24 top-0 right-0 px-2.5 bg-gradient-to-l from-white dark:from-gray-900"
                >
                    <button @click="slideRight()" type="button" class="flex items-center justify-center w-8 h-8 focus:outline-none rounded-full text-gray-400 dark:text-gray-500 hover:bg-gray-50 dark:bg-gray-800 transition duration-200 ease-in-out">
                        <x-untitledui-chevron-right class="w-6 h-6" aria-hidden="true" />
                    </button>
                </div>
            </div>
        </div>
    </x-slot>

    {{ $slot }}

</x-shopper::layouts.app>
