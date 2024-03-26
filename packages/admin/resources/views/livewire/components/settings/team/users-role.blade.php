<div class="overflow-hidden">
    <div class="px-4 2xl:px-6 flex flex-wrap items-baseline">
        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
            {{ __('shopper::words.users') }}
        </h3>
        <p class="ml-2 mt-1 text-sm leading-5 text-gray-500 truncate dark:text-gray-400">
            {{ __('shopper::pages/settings.roles_permissions.with_role_name', ['name' => $role->display_name]) }}
        </p>
    </div>
    <div class="mt-4 border-t border-gray-200 overflow-x-auto dark:border-gray-800">
        <div class="align-middle inline-block min-w-full">
            <table class="min-w-full">
                <thead>
                    <x-shopper::tables.table-row>
                        <x-shopper::tables.table-head>
                            <span class="lg:pl-2">{{ __('shopper::layout.forms.label.name') }}</span>
                        </x-shopper::tables.table-head>
                        <x-shopper::tables.table-head>
                            {{ __('shopper::layout.forms.label.email') }}
                        </x-shopper::tables.table-head>
                        <x-shopper::tables.table-head class="hidden lg:table-cell text-right">
                            {{ __('shopper::layout.forms.label.role') }}
                        </x-shopper::tables.table-head>
                        <x-shopper::tables.table-head class="hidden lg:table-cell text-right">
                            {{ __('shopper::layout.forms.label.access') }}
                        </x-shopper::tables.table-head>
                        <x-shopper::tables.table-head class="pr-6" />
                    </x-shopper::tables.table-row>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700" x-max="1">
                    @forelse($users as $user)
                        <tr>
                            <td class="px-6 py-3 whitespace-no-wrap text-sm leading-5 font-medium text-gray-900 dark:text-white">
                                <div class="flex items-center">
                                    <div class="shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full" src="{{ $user->picture }}" alt="User avatar">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm leading-5 font-medium">
                                            {{ $user->full_name }}
                                        </div>
                                        <div class="text-sm leading-5 text-gray-500 dark:text-gray-400">
                                            {{ __('shopper::words.registered_on') }} <time datetime="{{ $user->created_at->format('Y-m-d') }}" class="capitalize">{{ $user->created_at->formatLocalized('%d %B %Y') }}</time>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-3 text-sm leading-5 text-gray-500 dark:text-gray-400">
                                <div class="flex items-center">
                                    @if($user->email_verified_at)
                                        <x-untitledui-check-verified-02 class="w-5 h-5 text-green-500" />
                                    @else
                                        <x-untitledui-alert-circle class="w-5 h-5 text-danger-500" />
                                    @endif
                                    <span class="ml-1.5">{{ $user->email }}</span>
                                </div>
                            </td>
                            <td class="hidden lg:table-cell px-6 py-3 whitespace-no-wrap text-sm leading-5 text-gray-500 text-right">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400">
                                    {{ $user->roles_label }}
                                </span>
                            </td>
                            <td class="hidden lg:table-cell px-6 py-3 whitespace-no-wrap text-sm leading-5 text-gray-500 dark:text-gray-400 text-right">
                                {{ $user->hasRole(config('shopper.core.users.admin_role')) ? __('shopper::words.full') : __('shopper::words.limited') }}
                            </td>
                            <td class="pr-6 text-right">
                                @if($user->id === auth()->id())
                                    <span class="flex items-center text-sm leading-5 text-gray-500 text-right dark:text-gray-400">
                                        <x-untitledui-user-circle class="w-5 h-5 mr-2" />
                                        {{ __('shopper::words.me') }}
                                    </span>
                                @endif
                                @if(auth()->user()->isAdmin() && !$user->isAdmin())
                                    <x-shopper::dropdown customAlignmentClasses="right-12 -bottom-1">
                                        <x-slot name="trigger">
                                            <button id="admin-options-menu" aria-has-popup="true" :aria-expanded="open" type="button" class="w-8 h-8 inline-flex items-center justify-center text-gray-400 rounded-full bg-transparent hover:text-gray-500 focus:outline-none focus:text-gray-500 focus:bg-gray-100 dark:focus:bg-gray-700 transition ease-in-out duration-150">
                                                <x-untitledui-dots-vertical class="w-5 h-5" />
                                            </button>
                                        </x-slot>

                                        <x-slot name="content">
                                            <div class="py-1">
                                                <button wire:click="removeUser({{ $user->id }})" type="button" class="group flex w-full items-center px-4 py-2 text-sm leading-5 text-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-600 hover:text-gray-900 dark:hover:text-white" role="menuitem">
                                                    <x-untitledui-trash-03 class="mr-2 h-5 w-5 text-gray-400 group-hover:text-gray-500" />
                                                    {{ __('shopper::layout.forms.actions.delete') }}
                                                </button>
                                            </div>
                                        </x-slot>
                                    </x-shopper::dropdown>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 whitespace-no-wrap text-sm leading-5 font-medium">
                                <div class="flex flex-col justify-center items-center space-y-2">
                                    <x-untitledui-users
                                        class="h-10 w-10 text-primary-500"
                                        stroke-width="1.5"
                                        aria-hidden="true"
                                    />
                                    <span class="font-medium text-gray-500 text-xl dark:text-gray-400">
                                        {{ __('shopper::words.no_users') }}
                                    </span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="rounded-b-md px-4 py-3 flex items-center justify-between sm:px-6 border-t border-gray-200 dark:border-gray-700">
            <div class="flex-1 flex items-center">
                <div>
                    <p class="text-sm leading-5 text-gray-700 dark:text-gray-400">
                        {{ __('shopper::words.showing') }}
                        <span class="font-medium"> {!! $users->count() !!}</span>
                        {{ __('shopper::words.results') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
