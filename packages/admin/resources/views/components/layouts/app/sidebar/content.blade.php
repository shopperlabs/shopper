<div {{ $attributes->merge(['class' => 'bg-secondary-50 dark:bg-secondary-800 border-r border-transparent lg:border-secondary-200 lg:dark:border-secondary-700']) }}>
    <div class="flex flex-col h-full">
        <div class="bg-gradient-to-b from-secondary-200 dark:from-secondary-900">
            <div class="h-1 bg-gradient-to-br from-primary-600 to-primary-100 dark:to-primary-600/10"></div>
            <div class="px-4 py-5 flex items-start">
                <a class="shrink-0" href="{{ route('shopper.dashboard') }}">
                    <x-shopper::brand class="h-8 w-auto" />
                </a>
                <div class="ml-3 truncate">
                    <h4 class="text-base leading-4 font-medium text-secondary-900 dark:text-white truncate font-display">
                        {{ config('app.name') }}
                    </h4>
                    <span class="text-sm leading-5 font-medium text-secondary-500 dark:text-secondary-400">
                        {{ shopper_setting('shop_email') }}
                    </span>
                </div>
            </div>
        </div>
        <div class="flex-1 flex flex-col justify-between border-t border-secondary-200 dark:border-secondary-700 overflow-hidden">
            <div class="flex-1 h-full overflow-y-scroll scrolling">
                <x-shopper::layouts.app.sidebar.secondary />
            </div>

            <div class="p-4 border-t border-secondary-200 dark:border-secondary-700 space-y-2">
                @can('access_setting')
                    <a
                        href="{{ route('shopper.settings.index') }}"
                        @class([
                            'flex items-center p-2 text-sm leading-5 font-medium rounded-md transition ease-in-out duration-150',
                            'text-primary-500 bg-secondary-100 dark:bg-secondary-700/50' => request()->routeIs('shopper.settings*'),
                            'hover:bg-secondary-100 text-secondary-500 dark:text-secondary-400 hover:text-secondary-700 dark:hover:bg-secondary-700 dark:hover:text-white' => ! request()->routeIs('shopper.settings*')
                        ])
                    >
                        <x-heroicon-o-cog class="w-6 h-6 mr-2" />
                        {{ __('shopper::layout.account_dropdown.settings') }}
                    </a>
                @endcan

                <a
                    href="https://laravelshopper.dev"
                    target="_blank"
                    class="flex items-center p-2 text-sm leading-5 font-medium rounded-md text-secondary-500 hover:bg-secondary-100 hover:text-secondary-700 dark:text-secondary-400 dark:hover:bg-secondary-700 dark:hover:text-white transition ease-in-out duration-150"
                >
                    <svg class="w-6 h-6 mr-2 text-primary-500" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 9H2m12 8.5 2.5-2.5-2.5-2.5m-4 0L7.5 15l2.5 2.5M2 7.8v8.4c0 1.68 0 2.52.327 3.162a3 3 0 0 0 1.311 1.311C4.28 21 5.12 21 6.8 21h10.4c1.68 0 2.52 0 3.162-.327a3 3 0 0 0 1.311-1.311C22 18.72 22 17.88 22 16.2V7.8c0-1.68 0-2.52-.327-3.162a3 3 0 0 0-1.311-1.311C19.72 3 18.88 3 17.2 3H6.8c-1.68 0-2.52 0-3.162.327a3 3 0 0 0-1.311 1.311C2 5.28 2 6.12 2 7.8Z"/>
                    </svg>
                    {{ __('shopper::messages.dashboard.cards.doc_title') }}
                </a>

                <livewire:shopper-account.dropdown />
            </div>
        </div>
    </div>
</div>
