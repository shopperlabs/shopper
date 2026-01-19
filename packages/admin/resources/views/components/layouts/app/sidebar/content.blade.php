<div {{ $attributes }}>
    <div
        class="from-primary-600 to-primary-100 dark:to-primary-600/10 h-1 bg-linear-to-br"
    ></div>
    <div class="flex h-full flex-col">
        <div class="px-3 py-4">
            <div
                class="relative flex items-start rounded-lg bg-white px-3 py-2.5 shadow ring-1 ring-gray-200 dark:bg-white/5 dark:ring-gray-800"
            >
                <x-shopper::link class="shrink-0" :href="route('shopper.dashboard')">
                    <x-shopper::brand class="size-8" aria-hidden="true" />
                    <span class="absolute inset-0"></span>
                </x-shopper::link>
                <div class="ml-3 truncate">
                    <h4 class="font-heading truncate text-sm leading-4 font-medium text-gray-900 dark:text-white">
                        {{ shopper_setting('name') }}
                    </h4>
                    <span class="text-sm text-gray-500 dark:text-gray-400">
                        {{ shopper_setting('email') }}
                    </span>
                </div>
            </div>
        </div>
        <div class="flex flex-1 flex-col justify-between overflow-hidden">
            <div class="h-full flex-1 overflow-y-scroll">
                <x-shopper::layouts.app.sidebar.secondary />
            </div>

            <div class="space-y-2 border-t border-gray-200 p-4 dark:border-white/10">
                @can('access_setting')
                    <x-shopper::link
                        :href="route('shopper.settings.index')"
                        @class([
                            'sh-sidebar-item gap-2',
                            'sh-sidebar-item-active' => request()->routeIs('shopper.settings*'),
                            'sh-sidebar-item-inactive' => ! request()->routeIs('shopper.settings*'),
                        ])
                    >
                        <x-phosphor-faders
                            @class(['size-5', 'text-gray-400' => ! request()->routeIs('shopper.settings*')])
                            aria-hidden="true"
                        />
                        {{ __('shopper::pages/settings/global.menu') }}
                    </x-shopper::link>
                @endcan

                @if (! app()->isProduction())
                    <a
                        href="https://docs.laravelshopper.dev"
                        target="_blank"
                        class="sh-sidebar-item sh-sidebar-item-inactive gap-2"
                    >
                        <x-phosphor-brackets-curly class="size-5 text-gray-400 dark:text-gray-500" aria-hidden="true" />
                        {{ __('shopper::pages/dashboard.cards.doc_title') }}
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
