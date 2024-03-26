<div {{ $attributes }}>
    <div class="h-1 rounded-lg bg-gradient-to-br from-primary-600 to-primary-100 dark:to-primary-600/10"></div>
    <div class="flex flex-col h-full">
        <div class="px-4 py-5">
            <div class="relative flex items-start px-3 py-2.5 rounded-lg shadow-sm border border-gray-200 dark:shadow-lg dark:border-gray-800">
                <a class="shrink-0" href="{{ route('shopper.dashboard') }}">
                    <x-shopper::brand class="h-8 w-auto" />
                    <span class="absolute inset-0"></span>
                </a>
                <div class="ml-3 truncate">
                    <h4 class="text-sm font-medium leading-4 text-gray-900 dark:text-white truncate font-heading">
                        {{ config('app.name') }}
                    </h4>
                    <span class="text-sm text-gray-500 dark:text-gray-400">
                        {{ shopper_setting('email') }}
                    </span>
                </div>
            </div>
        </div>
        <div class="flex-1 flex flex-col justify-between overflow-hidden">
            <div class="flex-1 h-full overflow-y-scroll scrolling">
                <x-shopper::layouts.app.sidebar.secondary />
            </div>

            <div class="p-4 border-t border-gray-200 dark:border-gray-700 space-y-2">
                @can('access_setting')
                    <a
                        href="{{ route('shopper.settings.index') }}"
                        wire:navigate
                        @class([
                            'flex items-center p-2 text-sm font-medium leading-5 rounded-md transition ease-in-out duration-150',
                            'text-primary-500 bg-gray-100 dark:bg-gray-700/50' => request()->routeIs('shopper.settings*'),
                            'hover:bg-gray-50 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:bg-gray-700 dark:hover:text-white' => ! request()->routeIs('shopper.settings*')
                        ])
                    >
                        <x-untitledui-sliders class="mr-2 w-5 h-5" stroke-width="1.5" />
                        {{ __('shopper::layout.account_dropdown.settings') }}
                    </a>
                @endcan

                <a
                    href="https://laravelshopper.dev"
                    target="_blank"
                    class="flex items-center p-2 text-sm font-medium leading-5 rounded-md text-gray-500 hover:bg-gray-50 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white transition ease-in-out duration-150"
                >
                    <x-untitledui-code-browser class="mr-2 w-5 h-5" stroke-width="1.5" />
                    {{ __('shopper::messages.dashboard.cards.doc_title') }}
                </a>
            </div>
        </div>
    </div>
</div>
