<div {{ $attributes->merge(['class' => 'bg-white border-r border-transparent  dark:bg-gray-950 lg:border-secondary-200 lg:dark:border-gray-700']) }}>
    <div class="h-1 bg-gradient-to-br from-primary-600 to-primary-100 dark:to-primary-600/10"></div>
    <div class="flex flex-col h-full">
        <div class="px-4 py-5">
            <div class="relative flex items-start px-3 py-2.5 rounded-lg shadow-sm border border-secondary-200 dark:shadow-lg dark:border-gray-800">
                <a class="shrink-0" href="{{ route('shopper.dashboard') }}">
                    <x-shopper::brand class="h-8 w-auto" />
                    <span class="absolute inset-0"></span>
                </a>
                <div class="ml-3 truncate">
                    <h4 class="text-sm font-medium leading-4 text-secondary-900 dark:text-white truncate font-display">
                        {{ config('app.name') }}
                    </h4>
                    <span class="text-sm text-secondary-500 dark:text-secondary-400">
                        {{ shopper_setting('shop_email') }}
                    </span>
                </div>
            </div>
        </div>
        <div class="flex-1 flex flex-col justify-between overflow-hidden">
            <div class="flex-1 h-full overflow-y-scroll scrolling">
                <x-shopper::layouts.app.sidebar.secondary />
            </div>

            <div class="p-4 border-t border-secondary-200 dark:border-gray-700 space-y-2">
                @can('access_setting')
                    <a
                        href="{{ route('shopper.settings.index') }}"
                        @class([
                            'flex items-center p-2 text-sm font-medium leading-5 rounded-md transition ease-in-out duration-150',
                            'text-primary-500 bg-secondary-100 dark:bg-secondary-700/50' => request()->routeIs('shopper.settings*'),
                            'hover:bg-secondary-50 text-secondary-500 dark:text-secondary-400 hover:text-secondary-700 dark:hover:bg-secondary-700 dark:hover:text-white' => ! request()->routeIs('shopper.settings*')
                        ])
                    >
                        <x-untitledui-sliders class="mr-2 w-5 h-5" stroke-width="1.5" />
                        {{ __('shopper::layout.account_dropdown.settings') }}
                    </a>
                @endcan

                <a
                    href="https://laravelshopper.dev"
                    target="_blank"
                    class="flex items-center p-2 text-sm font-medium leading-5 rounded-md text-secondary-500 hover:bg-secondary-50 hover:text-secondary-700 dark:text-secondary-400 dark:hover:bg-secondary-700 dark:hover:text-white transition ease-in-out duration-150"
                >
                    <x-untitledui-code-browser class="mr-2 w-5 h-5" stroke-width="1.5" />
                    {{ __('shopper::messages.dashboard.cards.doc_title') }}
                </a>

                <livewire:shopper-account.dropdown />
            </div>
        </div>
    </div>
</div>
