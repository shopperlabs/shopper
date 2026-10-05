<div x-data="{ dropdownOpen: false }">
    <div class="group relative flex items-center rounded-xl transition duration-200 ease-in-out">
        <button
            @click="dropdownOpen = !dropdownOpen"
            class="relative inline-flex w-full items-center rounded-full text-sm leading-5"
            type="button"
        >
            <img class="size-7 rounded-full object-cover" src="{{ $user->picture }}" alt="{{ $user->email }}" />
            <span class="sr-only">{{ $user->full_name }}</span>
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
            class="bg-sh-card ring-sh-border absolute top-10 right-2 z-50 w-74 origin-top-right overflow-hidden rounded-xl shadow-md ring-1"
            x-ref="items"
            role="menu"
            aria-orientation="vertical"
            aria-labelledby="options-menu-button"
            tabindex="-1"
        >
            <div>
                <div class="ring-sh-border bg-sh-surface rounded-b-lg shadow-xs ring-1">
                    <div class="flex items-center gap-3 p-3">
                        <img
                            class="size-8 rounded-full object-cover"
                            src="{{ $user->picture }}"
                            alt="{{ $user->email }}"
                        />
                        <div class="min-w-0">
                            <p class="text-sh-fg truncate text-sm font-medium">
                                {{ $user->full_name }}
                            </p>
                            <p class="text-sh-fg-muted truncate text-xs">
                                {{ $user->email }}
                            </p>
                        </div>
                    </div>
                    <div class="p-1">
                        <x-shopper::dropdown-link :href="route('shopper.profile')">
                            <x-phosphor-user-circle class="text-sh-fg-muted size-5" aria-hidden="true" />
                            {{ __('shopper::layout.account_dropdown.personal_account') }}
                        </x-shopper::dropdown-link>
                        @can('system.users')
                            <x-shopper::dropdown-link :href="route('shopper.settings.users')">
                                <x-phosphor-users class="text-sh-fg-muted size-5" aria-hidden="true" />
                                {{ __('shopper::layout.account_dropdown.manage_users') }}
                            </x-shopper::dropdown-link>
                        @endcan

                        <div class="mt-1" role="none">
                            <form id="logout-form" action="{{ route('shopper.logout') }}" method="POST">
                                @csrf
                                <button
                                    type="submit"
                                    class="group text-sh-fg-secondary hover:bg-sh-muted flex w-full items-center gap-2 rounded-lg px-4 py-2 text-sm leading-5"
                                >
                                    <x-phosphor-sign-out class="text-sh-fg-muted size-5" aria-hidden="true" />
                                    {{ __('shopper::layout.account_dropdown.sign_out') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="p-1">
                    <x-shopper::theme-switcher />
                </div>
            </div>
        </div>
    </div>
</div>
