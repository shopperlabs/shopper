<x-shopper-dropdown align="left" width="56" customAlignmentClasses="right-1" containerClasses="relative" contentClasses="bg-white text-left">
    <x-slot name="trigger">
        <button class="max-w-xs flex items-center text-sm rounded-full overflow-hidden focus:outline-none focus:bg-gray-50 lg:p-1.5 lg:rounded-md lg:hover:bg-gray-50">
            <img class="h-8 w-8 rounded-full" src="{{ $picture }}" alt="{{ $email }}" />
            <span class="hidden ml-3 text-gray-700 text-sm leading-5 font-medium lg:block">{{ $full_name }}</span>
            <svg class="hidden flex-shrink-0 ml-1 h-5 w-5 text-gray-400 lg:block" x-description="Heroicon name: chevron-down" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </button>
    </x-slot>

    <x-slot name="content">
        <div class="px-4 py-3">
            <p class="text-sm leading-5">
                {{ __('Signed in with') }}
            </p>
            <p class="text-sm leading-5 font-medium text-gray-900 truncate">
                {{ $email }}
            </p>
        </div>
        @can('add_products')
            <div class="border-t border-gray-100"></div>
            <div class="py-1">
                <x-shopper-dropdown-link :href="route('shopper.products.create')">
                    <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500 group-focus:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    {{ __("Add product") }}
                </x-shopper-dropdown-link>
            </div>
        @endcan
        <div class="border-t border-gray-100"></div>
        <div class="py-1">
            <x-shopper-dropdown-link :href="route('shopper.profile')">
                <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500 group-focus:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ __("Personal Account") }}
            </x-shopper-dropdown-link>
            @can('view_users')
                <x-shopper-dropdown-link :href="route('shopper.settings.users')">
                    <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500 group-focus:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    {{ __("Manage Users") }}
                </x-shopper-dropdown-link>
            @endcan
        </div>
        <div class="border-t border-gray-100"></div>
        <div class="py-1">
            @can('access_setting')
                <x-shopper-dropdown-link :href="route('shopper.settings.index')">
                    <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500 group-focus:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    {{ __("Settings") }}
                </x-shopper-dropdown-link>
            @endcan
            <x-shopper-dropdown-link href="#">
                <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500 group-focus:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path  stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                </svg>
                {{ __("Dark Mode") }}
                <span class="inline-flex items-center ml-1 px-2.5 py-0.5 rounded-full text-xs font-medium leading-4 bg-blue-100 text-blue-800">
                    {{ __("Soon") }}
                </span>
            </x-shopper-dropdown-link>
        </div>
        <div class="border-t border-gray-100"></div>
        <div class="py-1">
            <x-shopper-dropdown-link
                :href="route('shopper.logout')"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
            >
                <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500 group-focus:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                </svg>
                {{ __("Sign out") }}
            </x-shopper-dropdown-link>
            <form id="logout-form" action="{{ route('shopper.logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </x-slot>
</x-shopper-dropdown>
