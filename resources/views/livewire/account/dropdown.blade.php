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
                    <x-heroicon-o-plus-sm class="mr-3 h-5 w-5 text-secondary-400 group-hover:text-secondary-500 group-focus:text-secondary-500" />
                    {{ __('shopper::layout.account_dropdown.add_product') }}
                </x-shopper::dropdown-link>
            </div>
        @endcan
        <div class="border-t border-secondary-100 dark:border-secondary-700"></div>
        <div class="py-1">
            <x-shopper::dropdown-link :href="route('shopper.profile')">
                <x-heroicon-o-user-circle class="mr-3 h-5 w-5 text-secondary-400 group-hover:text-secondary-500 group-focus:text-secondary-500" />
                {{ __('shopper::layout.account_dropdown.personal_account') }}
            </x-shopper::dropdown-link>
            @can('view_users')
                <x-shopper::dropdown-link :href="route('shopper.settings.users')">
                    <x-heroicon-o-users class="mr-3 h-5 w-5 text-secondary-400 group-hover:text-secondary-500 group-focus:text-secondary-500" />
                    {{ __('shopper::layout.account_dropdown.manage_users') }}
                </x-shopper::dropdown-link>
            @endcan
        </div>
        <div class="border-t border-secondary-100 dark:border-secondary-700"></div>
        <div class="py-1">
            @can('access_setting')
                <x-shopper::dropdown-link :href="route('shopper.settings.index')">
                    <x-heroicon-o-cog class="mr-3 h-5 w-5 text-secondary-400 group-hover:text-secondary-500 group-focus:text-secondary-500" />
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
