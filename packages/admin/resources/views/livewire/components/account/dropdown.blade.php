<div x-data="{ dropdownOpen: false }">
    <div class="group relative flex items-center rounded-xl transition duration-200 ease-in-out">
        <button
            @click="dropdownOpen = !dropdownOpen"
            class="relative inline-flex w-full items-center rounded-full text-sm leading-5 focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800"
            type="button"
        >
            <img class="size-8 rounded-full" src="{{ $user->picture }}" alt="{{ $user->email }}" />
            <span class="sr-only">{{ $user->full_name }}</span>
            <span
                class="absolute bottom-0 right-0 block h-2.5 w-2.5 rounded-full bg-green-400 ring-2 ring-white dark:ring-white/10"
            ></span>
        </button>
        <div
            x-show="dropdownOpen"
            x-transition:enter="transition duration-100 ease-out"
            x-transition:enter-start="scale-95 transform opacity-0"
            x-transition:enter-end="scale-100 transform opacity-100"
            x-transition:leave="transition duration-75 ease-in"
            x-transition:leave-start="scale-100 transform opacity-100"
            x-transition:leave-end="scale-95 transform opacity-0"
            @click.outside="dropdownOpen = false"
            x-cloak
            class="absolute right-2.5 top-10 z-50 w-56 origin-top-right rounded-lg bg-white shadow-lg ring-1 ring-gray-200 dark:bg-gray-900 dark:ring-white/5"
            x-ref="items"
            role="menu"
            aria-orientation="vertical"
            aria-labelledby="options-menu-button"
            tabindex="-1"
        >
            <div class="divide-y divide-gray-100 dark:divide-white/10">
                <p class="truncate px-3.5 py-3">
                    <span class="block text-xs text-gray-500 dark:text-gray-400" role="none">
                        {{ __('shopper::words.sign_in_as') }}
                    </span>
                    <span class="mt-0.5 text-sm font-medium text-gray-900 dark:text-white" role="none">
                        {{ $user->email }}
                    </span>
                </p>
                <div class="py-1.5">
                    <x-shopper::dropdown-link :href="route('shopper.profile')">
                        {{ __('shopper::layout.account_dropdown.personal_account') }}
                    </x-shopper::dropdown-link>
                    @can('view_users')
                        <x-shopper::dropdown-link :href="route('shopper.settings.users')">
                            {{ __('shopper::layout.account_dropdown.manage_users') }}
                        </x-shopper::dropdown-link>
                    @endcan
                </div>
                <div class="px-1 py-1.5">
                    <x-shopper::theme-switcher />
                </div>
                <div class="px-1 py-1.5" role="none">
                    <form id="logout-form" action="{{ route('shopper.logout') }}" method="POST">
                        @csrf
                        <button
                            type="submit"
                            class="group flex w-full items-center rounded-lg px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-white/5"
                        >
                            <x-untitledui-log-out
                                class="mr-2 size-5 text-gray-400 dark:group-hover:text-gray-500"
                                aria-hidden="true"
                            />
                            {{ __('shopper::layout.account_dropdown.sign_out') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
