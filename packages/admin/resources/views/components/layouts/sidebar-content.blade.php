<!-- Navigation -->
<div class="flex min-h-0 flex-1 flex-col justify-between">
    <div class="relative min-h-0 flex-1">
        <!-- Top fade gradient -->
        <div
            class="pointer-events-none absolute top-0 right-0.5 left-0 z-10 h-6 bg-linear-to-b from-gray-50 to-transparent dark:from-gray-950"
        ></div>

        <div class="h-full overflow-y-auto">
            <nav class="sh-si-nav px-4 py-4">
                {!! $renderedSidebar !!}
            </nav>
        </div>

        <!-- Bottom fade gradient -->
        <div
            class="pointer-events-none absolute right-0.5 bottom-0 left-0 z-10 h-6 bg-linear-to-t from-gray-50 to-transparent dark:from-gray-950"
        ></div>
    </div>

    <!-- Footer -->
    <div class="sh-sidebar border-t border-gray-200 px-4 pt-4 pb-10 dark:border-white/20">
        <div class="sh-sidebar-group">
            <ul role="list" class="sh-sidebar-group-items space-y-1">
                @can('access_setting')
                    <li
                        class="sh-sidebar-item"
                        x-data="{ url: {{ Js::from(route('shopper.settings.index')) }} }"
                        x-bind:class="{ 'sh-sidebar-item-active': $store.sidebar?.isActive(url) }"
                    >
                        <x-shopper::link
                            :href="route('shopper.settings.index')"
                            class="sh-sidebar-item-link"
                            x-tooltip="{
                                content: {{ Js::from(__('shopper::pages/settings/global.menu')) }},
                                placement: 'right',
                                theme: $store.theme,
                                onShow: () => $store.sidebar?.isCollapsed,
                            }"
                        >
                            <x-phosphor-faders class="sh-sidebar-item-icon" aria-hidden="true" />
                            <span
                                class="sh-sidebar-item-label"
                                x-show="!$store.sidebar.isCollapsed"
                                x-transition:enter="transition-opacity duration-200"
                                x-transition:enter-start="opacity-0"
                                x-transition:enter-end="opacity-100"
                            >
                                {{ __('shopper::pages/settings/global.menu') }}
                            </span>
                        </x-shopper::link>
                    </li>
                @endcan

                @if (! app()->isProduction())
                    <li class="sh-sidebar-item">
                        <a
                            href="https://docs.laravelshopper.dev"
                            target="_blank"
                            class="sh-sidebar-item-link"
                            x-tooltip="{
                                content: {{ Js::from(__('shopper::pages/dashboard.cards.doc_title')) }},
                                placement: 'right',
                                theme: $store.theme,
                                onShow: () => $store.sidebar?.isCollapsed,
                            }"
                        >
                            <x-phosphor-brackets-curly class="sh-sidebar-item-icon" aria-hidden="true" />
                            <span
                                class="sh-sidebar-item-label"
                                x-show="!$store.sidebar.isCollapsed"
                                x-transition:enter="transition-opacity duration-200"
                                x-transition:enter-start="opacity-0"
                                x-transition:enter-end="opacity-100"
                            >
                                {{ __('shopper::pages/dashboard.cards.doc_title') }}
                            </span>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>
