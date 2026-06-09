<div x-data="{ dropdownOpen: false }">
    <div class="group relative flex items-center rounded-xl transition duration-200 ease-in-out">
        <button
            @click="dropdownOpen = !dropdownOpen"
            class="relative inline-flex w-full items-center rounded-full text-sm leading-5"
            type="button"
        >
            <img class="size-7 rounded-full" src="{{ $user->picture }}" alt="{{ $user->email }}" />
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
            class="absolute top-10 right-2 z-50 w-74 origin-top-right rounded-xl overflow-hidden bg-sh-card shadow-md ring-1 ring-sh-border"
            x-ref="items"
            role="menu"
            aria-orientation="vertical"
            aria-labelledby="options-menu-button"
            tabindex="-1"
        >
            <div>
                <div class="rounded-b-lg ring-1 ring-sh-border bg-sh-surface shadow-xs">
                    <div class="flex items-center gap-3 p-3">
                        <img class="size-8 rounded-full" src="{{ $user->picture }}" alt="{{ $user->email }}" />
                        <div class="min-w-0">
                            <p class="truncate text-sm font-medium text-sh-fg">
                                {{ $user->full_name }}
                            </p>
                            <p class="truncate text-xs text-sh-fg-muted">
                                {{ $user->email }}
                            </p>
                        </div>
                    </div>
                    <div class="p-1">
                        <x-shopper::dropdown-link :href="route('shopper.profile')">
                            <x-phosphor-user-circle class="size-5 text-sh-fg-muted" aria-hidden="true" />
                            {{ __('shopper::layout.account_dropdown.personal_account') }}
                        </x-shopper::dropdown-link>
                        @can('system.users')
                            <x-shopper::dropdown-link :href="route('shopper.settings.users')">
                                <x-phosphor-users class="size-5 text-sh-fg-muted" aria-hidden="true" />
                                {{ __('shopper::layout.account_dropdown.manage_users') }}
                            </x-shopper::dropdown-link>
                        @endcan
                        <div class="mt-1" role="none">
                            <form id="logout-form" action="{{ route('shopper.logout') }}" method="POST">
                                @csrf
                                <button
                                    type="submit"
                                    class="group flex w-full items-center gap-2 rounded-lg px-4 py-2 text-sm leading-5 text-sh-fg-secondary hover:bg-sh-muted"
                                >
                                    <x-phosphor-sign-out
                                        class="size-5 text-sh-fg-muted"
                                        aria-hidden="true"
                                    />
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
