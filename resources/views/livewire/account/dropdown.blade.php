<x-shopper::dropdown align="left" width="56" customAlignmentClasses="right-1" containerClasses="relative" contentClasses="bg-white dark:bg-secondary-800 text-left">
    <x-slot name="trigger">
        <button class="max-w-xs flex items-center text-sm rounded-full overflow-hidden focus:outline-none hover:bg-secondary-50 dark:focus:bg-secondary-700 dark:hover:bg-secondary-800 lg:p-1.5 lg:rounded-md">
            <img class="h-8 w-8 rounded-full" src="{{ $picture }}" alt="{{ $email }}" />
            <span class="hidden ml-3 text-secondary-900 dark:text-white text-sm leading-5 font-medium lg:block">{{ $full_name }}</span>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="hidden shrink-0 ml-1 h-5 w-5 text-secondary-400 lg:inline">
                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
            </svg>
        </button>
    </x-slot>

    <x-slot name="content">
        <div class="px-4 py-3">
            <p class="text-sm leading-5 txt-secondary-900 dark:text-white">
                {{ __('shopper::layout.account_dropdown.sign_in') }}
            </p>
            <p class="text-sm leading-5 font-medium text-secondary-900 dark:text-white truncate">
                {{ $email }}
            </p>
        </div>
        @can('add_products')
            <div class="border-t border-secondary-100 dark:border-secondary-700"></div>
            <div class="py-1">
                <x-shopper::dropdown-link :href="route('shopper.products.create')">
                    <svg class="mr-3 h-5 w-5 text-secondary-400 group-hover:text-secondary-500 group-focus:text-secondary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                    </svg>
                    {{ __('shopper::layout.account_dropdown.add_product') }}
                </x-shopper::dropdown-link>
            </div>
        @endcan
        <div class="border-t border-secondary-100 dark:border-secondary-700"></div>
        <div class="py-1">
            <x-shopper::dropdown-link :href="route('shopper.profile')">
                <svg class="mr-3 h-5 w-5 text-secondary-400 group-hover:text-secondary-500 group-focus:text-secondary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                {{ __('shopper::layout.account_dropdown.personal_account') }}
            </x-shopper::dropdown-link>
            @can('view_users')
                <x-shopper::dropdown-link :href="route('shopper.settings.users')">
                    <svg class="mr-3 h-5 w-5 text-secondary-400 group-hover:text-secondary-500 group-focus:text-secondary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                    {{ __('shopper::layout.account_dropdown.manage_users') }}
                </x-shopper::dropdown-link>
            @endcan
        </div>
        <div class="border-t border-secondary-100 dark:border-secondary-700"></div>
        <div class="py-1">
            @can('access_setting')
                <x-shopper::dropdown-link :href="route('shopper.settings.index')">
                    <svg class="mr-3 h-5 w-5 text-secondary-400 group-hover:text-secondary-500 group-focus:text-secondary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    {{ __('shopper::layout.account_dropdown.settings') }}
                </x-shopper::dropdown-link>
            @endcan
        </div>
        <div class="border-t border-secondary-100 dark:border-secondary-700"></div>
        <div class="py-1">
            <x-shopper::dropdown-link
                :href="route('shopper.logout')"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
            >
                <svg class="mr-3 h-5 w-5 text-secondary-400 group-hover:text-secondary-500 group-focus:text-secondary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                </svg>
                {{ __('shopper::layout.account_dropdown.sign_out') }}
            </x-shopper::dropdown-link>
            <form id="logout-form" action="{{ route('shopper.logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </x-slot>
</x-shopper::dropdown>
