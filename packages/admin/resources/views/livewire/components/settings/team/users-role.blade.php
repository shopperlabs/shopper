<div class="overflow-hidden">
    <div class="flex flex-wrap items-baseline px-4 2xl:px-6">
        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">
            {{ __('shopper::words.users') }}
        </h3>
        <p class="ml-2 mt-1 truncate text-sm leading-5 text-gray-500 dark:text-gray-400">
            {{ __('shopper::pages/settings.roles_permissions.with_role_name', ['name' => $role->display_name]) }}
        </p>
    </div>
    <div class="mt-4 overflow-x-auto border-t border-gray-200 dark:border-gray-800">
        <div class="inline-block min-w-full align-middle">
            <table class="min-w-full">
                <thead>
                    <x-shopper::tables.table-row>
                        <x-shopper::tables.table-head>
                            <span class="lg:pl-2">{{ __('shopper::layout.forms.label.name') }}</span>
                        </x-shopper::tables.table-head>
                        <x-shopper::tables.table-head>
                            {{ __('shopper::layout.forms.label.email') }}
                        </x-shopper::tables.table-head>
                        <x-shopper::tables.table-head class="hidden text-right lg:table-cell">
                            {{ __('shopper::layout.forms.label.role') }}
                        </x-shopper::tables.table-head>
                        <x-shopper::tables.table-head class="hidden text-right lg:table-cell">
                            {{ __('shopper::layout.forms.label.access') }}
                        </x-shopper::tables.table-head>
                        <x-shopper::tables.table-head class="pr-6" />
                    </x-shopper::tables.table-row>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700" x-max="1">
                    @forelse ($users as $user)
                        <tr>
                            <x-shopper::tables.table-cell class="whitespace-no-wrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 shrink-0">
                                        <img
                                            class="h-10 w-10 rounded-full"
                                            src="{{ $user->picture }}"
                                            alt="User avatar"
                                        />
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium leading-5">
                                            {{ $user->full_name }}
                                        </div>
                                        <div class="text-sm leading-5 text-gray-500 dark:text-gray-400">
                                            {{ __('shopper::words.registered_on') }}
                                            <time
                                                datetime="{{ $user->created_at->format('Y-m-d') }}"
                                                class="capitalize"
                                            >
                                                {{ $user->created_at->formatLocalized('%d %B %Y') }}
                                            </time>
                                        </div>
                                    </div>
                                </div>
                            </x-shopper::tables.table-cell>
                            <x-shopper::tables.table-cell>
                                <div class="flex items-center">
                                    @if ($user->email_verified_at)
                                        <x-untitledui-check-verified-02 class="h-5 w-5 text-green-500" />
                                    @else
                                        <x-untitledui-alert-circle class="h-5 w-5 text-danger-500" />
                                    @endif
                                    <span class="ml-1.5">{{ $user->email }}</span>
                                </div>
                            </x-shopper::tables.table-cell>
                            <x-shopper::tables.table-cell class="whitespace-no-wrap hidden lg:table-cell">
                                <span
                                    class="inline-flex rounded-full bg-gray-100 px-2 text-xs font-semibold leading-5 text-gray-800 dark:bg-gray-700 dark:text-gray-400"
                                >
                                    {{ $user->roles_label }}
                                </span>
                            </x-shopper::tables.table-cell>
                            <x-shopper::tables.table-cell class="whitespace-no-wrap hidden text-right lg:table-cell">
                                {{ $user->hasRole(config('shopper.core.users.admin_role')) ? __('shopper::words.full') : __('shopper::words.limited') }}
                            </x-shopper::tables.table-cell>
                            <x-shopper::tables.table-cell class="pr-6 text-right">
                                @if ($user->id === auth()->id())
                                    <span
                                        class="flex items-center text-right text-sm leading-5 text-gray-500 dark:text-gray-400"
                                    >
                                        <x-untitledui-user-circle class="mr-2 h-5 w-5" />
                                        {{ __('shopper::words.me') }}
                                    </span>
                                @endif

                                @if (auth()->user()->isAdmin() && ! $user->isAdmin())
                                    <x-shopper::dropdown customAlignmentClasses="right-12 -bottom-1">
                                        <x-slot name="trigger">
                                            <button
                                                id="admin-options-menu"
                                                aria-has-popup="true"
                                                :aria-expanded="open"
                                                type="button"
                                                class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-transparent text-gray-400 transition duration-150 ease-in-out hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none dark:focus:bg-gray-700"
                                            >
                                                <x-untitledui-dots-vertical class="h-5 w-5" />
                                            </button>
                                        </x-slot>

                                        <x-slot name="content">
                                            <div class="py-1">
                                                <button
                                                    wire:click="removeUser({{ $user->id }})"
                                                    type="button"
                                                    class="group flex w-full items-center px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-white"
                                                    role="menuitem"
                                                >
                                                    <x-untitledui-trash-03
                                                        class="mr-2 h-5 w-5 text-gray-400 group-hover:text-gray-500"
                                                    />
                                                    {{ __('shopper::layout.forms.actions.delete') }}
                                                </button>
                                            </div>
                                        </x-slot>
                                    </x-shopper::dropdown>
                                @endif
                            </x-shopper::tables.table-cell>
                        </tr>
                    @empty
                        <tr>
                            <x-shopper::tables.table-cell colspan="5" class="whitespace-no-wrap px-6 py-10">
                                <div class="flex flex-col items-center justify-center space-y-2">
                                    <x-untitledui-users
                                        class="h-10 w-10 text-primary-500"
                                        stroke-width="1.5"
                                        aria-hidden="true"
                                    />
                                    <span class="text-xl font-medium text-gray-500 dark:text-gray-400">
                                        {{ __('shopper::words.no_users') }}
                                    </span>
                                </div>
                            </x-shopper::tables.table-cell>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div
            class="flex items-center justify-between rounded-b-md border-t border-gray-200 px-4 py-3 dark:border-gray-700 sm:px-6"
        >
            <div class="flex flex-1 items-center">
                <div>
                    <p class="text-sm leading-5 text-gray-700 dark:text-gray-400">
                        {{ __('shopper::words.showing') }}
                        <span class="font-medium">{!! $users->count() !!}</span>
                        {{ __('shopper::words.results') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
