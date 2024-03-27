<div x-data="{ dropdownOpen: false }">
    <div class="relative flex items-center rounded-lg group transition ease-in-out duration-200">
        <button
            @click="dropdownOpen = !dropdownOpen"
            class="relative inline-flex items-center w-full text-sm leading-5 rounded-full focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800"
            type="button">
            <img class="h-8 w-8 rounded-full"
                 src="{{ $user->picture }}"
                 alt="{{ $user->email }}"
            />
            <span class="sr-only">{{ $user->full_name }}</span>
            <span class="absolute bottom-0 right-0 block h-2.5 w-2.5 rounded-full bg-green-400 ring-2 ring-white dark:ring-gray-700"></span>
        </button>
        <div x-show="dropdownOpen"
             x-transition:enter="transition ease-out duration-100"
             x-transition:enter-start="transform opacity-0 scale-95"
             x-transition:enter-end="transform opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="transform opacity-100 scale-100"
             x-transition:leave-end="transform opacity-0 scale-95"
             @click.outside="dropdownOpen = false"
             x-cloak
             class="absolute z-50 top-10 right-0 w-56 origin-top-right rounded-lg backdrop-blur-sm bg-white/60 shadow-lg ring-1 ring-gray-200 dark:bg-gray-900/80 dark:ring-gray-800"
             x-ref="items"
             role="menu"
             aria-orientation="vertical"
             aria-labelledby="options-menu-button"
             tabindex="-1"
        >
            <div class="divide-y divide-gray-100 dark:divide-gray-700">
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
                <div class="py-1.5">
                    <div
                        x-data="{
                        theme: null,
                        mode: null,

                        init: function () {
                            this.theme = localStorage.getItem('theme') || (this.isSystemDark() ? 'dark' : 'light')
                            this.mode = localStorage.getItem('theme') ? 'manual' : 'auto'

                            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (event) => {
                                if (this.mode === 'manual') return

                                if (event.matches && (! document.documentElement.classList.contains('dark'))) {
                                    this.theme = 'dark'

                                    document.documentElement.classList.add('dark')
                                } else if ((! event.matches) && document.documentElement.classList.contains('dark')) {
                                    this.theme = 'light'

                                    document.documentElement.classList.remove('dark')
                                }
                            })

                            $watch('theme', () => {
                                if (this.mode === 'auto') return

                                localStorage.setItem('theme', this.theme)

                                if (this.theme === 'dark' && (! document.documentElement.classList.contains('dark'))) {
                                    document.documentElement.classList.add('dark')
                                } else if (this.theme === 'light' && document.documentElement.classList.contains('dark')) {
                                    document.documentElement.classList.remove('dark')
                                }

                                $dispatch('dark-mode-toggled', this.theme)
                            })
                        },

                        isSystemDark: function () {
                            return window.matchMedia('(prefers-color-scheme: dark)').matches
                        },
                    }"
                        class="flex items-center px-4 py-2 gap-x-4 text-sm leading-5 text-gray-700 dark:text-gray-400 transition duration-150 ease-in-out"
                    >
                        <button
                            type="button"
                            aria-pressed="false"
                            @click="mode = 'manual'; theme === 'dark' ? theme = 'light' : theme = 'dark'"
                            class="bg-gray-200 dark:bg-gray-900 relative flex items-center shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-primary-800 focus:ring-gray-500"
                        >
                            <span class="translate-x-0 dark:translate-x-5 relative inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200">
                                <span class="dark:hidden ease-in-out duration-100 absolute inset-0 h-full w-full flex items-center justify-center" aria-hidden="true">
                                    <svg class="h-3 w-3 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 2.25a.75.75 0 01.75.75v2.25a.75.75 0 01-1.5 0V3a.75.75 0 01.75-.75zM7.5 12a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM18.894 6.166a.75.75 0 00-1.06-1.06l-1.591 1.59a.75.75 0 101.06 1.061l1.591-1.59zM21.75 12a.75.75 0 01-.75.75h-2.25a.75.75 0 010-1.5H21a.75.75 0 01.75.75zM17.834 18.894a.75.75 0 001.06-1.06l-1.59-1.591a.75.75 0 10-1.061 1.06l1.59 1.591zM12 18a.75.75 0 01.75.75V21a.75.75 0 01-1.5 0v-2.25A.75.75 0 0112 18zM7.758 17.303a.75.75 0 00-1.061-1.06l-1.591 1.59a.75.75 0 001.06 1.061l1.591-1.59zM6 12a.75.75 0 01-.75.75H3a.75.75 0 010-1.5h2.25A.75.75 0 016 12zM6.697 7.757a.75.75 0 001.06-1.06l-1.59-1.591a.75.75 0 00-1.061 1.06l1.59 1.591z" />
                                    </svg>
                                </span>
                                <span class="hidden dark:flex absolute inset-0 h-full w-full items-center justify-center ease-in-out duration-200" aria-hidden="true">
                                    <svg class="h-3 w-3 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                                      <path fill-rule="evenodd" d="M9.528 1.718a.75.75 0 01.162.819A8.97 8.97 0 009 6a9 9 0 009 9 8.97 8.97 0 003.463-.69.75.75 0 01.981.98 10.503 10.503 0 01-9.694 6.46c-5.799 0-10.5-4.701-10.5-10.5 0-4.368 2.667-8.112 6.46-9.694a.75.75 0 01.818.162z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                            </span>
                        </button>
                        <span>{{ __('shopper::words.dark_mode') }}</span>
                    </div>
                </div>
                <div class="py-1.5" role="none">
                    <x-shopper::dropdown-link
                        :href="route('shopper.logout')"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    >
                        {{ __('shopper::layout.account_dropdown.sign_out') }}
                    </x-shopper::dropdown-link>
                    <form id="logout-form" action="{{ route('shopper.logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
