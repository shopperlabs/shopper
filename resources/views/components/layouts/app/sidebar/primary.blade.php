<div class="flex flex-col justify-between bg-primary-800 text-center w-16">
    <div>
        <div class="px-2 bg-white">
            <a class="shrink-0 flex items-center justify-center p-2" href="{{ route('shopper.dashboard') }}">
                <x-shopper::brand class="h-8 w-auto" />
            </a>
        </div>
        <div class="h-full pt-5 pb-4 overflow-y-auto">
            <ul class="mt-5 px-2 space-y-2 flex flex-col items-center">
                <x-shopper::menu.item
                    :href="route('shopper.dashboard')"
                    :active="request()->routeIs('shopper.dashboard')"
                    x-tooltip.raw="{{ __('shopper::layout.sidebar.dashboard') }}"
                >
                    <svg class="h-6 w-6 text-white" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </x-shopper::menu.item>
            </ul>
        </div>
    </div>
    <div class="shrink-0 flex flex-col items-center space-y-3 pb-4">
        <button
            type="button"
            aria-pressed="false"
            class="darkModeToggle bg-secondary-200 dark:bg-secondary-800 relative inline-flex shrink-0 h-6 w-11 my-3 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-primary-800 focus:ring-secondary-500"
        >
            <span class="translate-x-0 dark:translate-x-5 relative inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200">
                <span class="dark:hidden opacity-100 ease-in duration-200 dark:opacity-0 dark:ease-out dark:duration-100 absolute inset-0 h-full w-full flex items-center justify-center transition-opacity" aria-hidden="true">
                    <x-heroicon-s-sun class="h-3 w-3 text-secondary-400" />
                </span>
                <span class="hidden dark:flex opacity-0 ease-out duration-100 dark:opacity-0 dark:ease-out dark:duration-100 absolute inset-0 h-full w-full items-center justify-center transition-opacity opacity-10 ease-in duration-200 " aria-hidden="true">
                    <x-heroicon-s-moon class="h-3 w-3 text-secondary-600" />
                </span>
            </span>
        </button>

        @can('access_setting')
            <a
                href="{{ route('shopper.settings.index') }}"
                class="block p-2 text-base leading-6 font-medium rounded-md text-white hover:bg-primary-700 @if(request()->routeIs('shopper.settings*')) bg-primary-700 @endif focus:outline-none focus:bg-primary-900 transition ease-in-out duration-150"
                x-tooltip.raw="{{ __('shopper::layout.account_dropdown.settings') }}"
            >
                <x-heroicon-o-cog class="w-6 h-6"/>
            </a>
        @endcan

        <a
            href="https://laravelshopper.dev"
            target="_blank"
            class="block p-2 text-base leading-6 font-medium rounded-md text-white hover:bg-primary-700 focus:outline-none focus:bg-primary-900 transition ease-in-out duration-150"
            x-tooltip.raw="{{ __('shopper::messages.dashboard.cards.doc_title') }}"
        >
            <x-heroicon-o-code class="w-6 h-6"/>
        </a>
    </div>
</div>
