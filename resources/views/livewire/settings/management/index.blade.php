<div>
    <x-shopper-breadcrumb back="shopper.settings.index">
        <x-heroicon-s-chevron-left class="shrink-0 h-5 w-5 text-secondary-400" />
        <x-shopper-breadcrumb-link :link="route('shopper.settings.index')" title="Settings" />
    </x-shopper-breadcrumb>

    <x-shopper-heading class="mt-3">
        <x-slot name="title">
            {{ __('Administrators & roles') }}
        </x-slot>
    </x-shopper-heading>

    <div class="mt-8 pb-10">
        <div class="bg-white dark:bg-secondary-800 p-4 sm:p-6 rounded-lg shadow-md overflow-hidden">
            <div class="flex items-center">
                <h2 class="text-lg leading-6 font-medium text-secondary-900 dark:text-white">{{ __('Administrator role available') }}</h2>
                <button wire:click="$emit('openModal', 'shopper-modals.create-role')" type="button" class="ml-3 inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs leading-4 font-medium rounded text-primary-700 bg-primary-100 hover:bg-primary-50 focus:outline-none focus:border-primary-300 focus:shadow-outline-primary active:bg-primary-200 transition ease-in-out duration-150">
                    <x-heroicon-s-plus-sm class="w-4 h-4 mr-1" />
                    {{ __('Add new role') }}
                </button>
            </div>
            <p class="mt-3 text-base leading-6 text-secondary-500 dark:text-secondary-400">
                {{ __('A role provides access to predefined menus and features so that depending on the assigned role and permissions an administrator can have access to what he needs.') }}
            </p>
            <div class="mt-8 grid gap-4 sm:grid-cols-2 sm:gap-6 md:grid-cols-3 md:gap-8">
                @foreach($roles as $role)
                    <a href="{{ route('shopper.settings.user.role', $role) }}" class="group flex flex-col justify-between border border-secondary-200 dark:border-secondary-700 p-4 rounded-md overflow-hidden hover:shadow-md">
                        <div class="flex items-center justify-between">
                            <span class="text-xs leading-4 text-secondary-400 dark:text-secondary-500 font-semibold uppercase tracking-wider">{{ $role->users->count() }} {{ str_plural(__('Account'), $role->users->count()) }}</span>
                            <div class="flex overflow-hidden ml-4">
                                @foreach($role->users as $admin)
                                    <img class="{{ $loop->first ? '' : '-ml-1' }} inline-block h-6 w-6 rounded-full shadow-solid" src="{{ $admin->picture }}" alt="">
                                @endforeach
                            </div>
                        </div>
                        <div>
                            <h3 class="mt-4 text-lg leading-6 font-medium text-secondary-900 dark:text-white">{{ $role->display_name }}</h3>
                            <p class="mt-1 flex items-center text-sm text-primary-600 group-hover:text-primary-500">
                                {{ __('View details') }}
                                <span class="ml-2">
                                    <x-heroicon-o-arrow-narrow-right class="w-5 h-5" />
                                </span>
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
        <div class="mt-10 bg-white dark:bg-secondary-800 p-4 sm:p-6 rounded-lg shadow-md">
            <div class="pb-6 border-b border-secondary-200 space-y-3 sm:flex sm:items-center sm:justify-between sm:space-x-4 sm:space-y-0  dark:border-secondary-700">
                <div class="flex-1 min-w-0 max-w-2xl">
                    <h2 class="text-lg leading-6 font-medium text-secondary-900 dark:text-white">{{ __('Administrators accounts') }}</h2>
                    <p class="mt-3 text-base leading-6 text-secondary-500 dark:text-secondary-400">
                        {{ __('These are the members who are already in your store with their associated roles. You can assign new roles to existing member here.') }}
                    </p>
                </div>
                <div>
                    <x-shopper-button :link="route('shopper.settings.user.new')">
                        <x-heroicon-o-user-add class="w-5 h-5 mr-1.5" />
                        {{ __('Add administrator') }}
                    </x-shopper-button>
                </div>
            </div>
            <div class="mt-6 border border-secondary-200 rounded-md dark:border-secondary-700">
                <div class="overflow-x-auto">
                    <div class="align-middle inline-block min-w-full">
                        <table class="min-w-full">
                            <thead>
                                <tr class="border-b border-secondary-200 bg-secondary-50 dark:border-secondary-700 dark:bg-secondary-700">
                                    <x-shopper-tables.table-head>
                                        <span class="lg:pl-2">{{ __('Name') }}</span>
                                    </x-shopper-tables.table-head>
                                    <x-shopper-tables.table-head>
                                        {{ __('Email Address') }}
                                    </x-shopper-tables.table-head>
                                    <x-shopper-tables.table-head class="hidden md:table-cell text-right">
                                        {{ __('Role') }}
                                    </x-shopper-tables.table-head>
                                    <x-shopper-tables.table-head class="hidden md:table-cell text-right">
                                        {{ __('Access') }}
                                    </x-shopper-tables.table-head>
                                    <x-shopper-tables.table-head class="pr-6" />
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-secondary-100 bg-white dark:bg-secondary-800 dark:divide-secondary-700" x-max="1">
                                @foreach($users as $user)
                                    <tr>
                                        <td class="px-6 py-3 whitespace-no-wrap text-sm leading-5 font-medium text-secondary-900 dark:text-white">
                                            <div class="flex items-center">
                                                <div class="shrink-0 h-10 w-10">
                                                    <img class="h-10 w-10 rounded-full" src="{{ $user->picture }}" alt="User avatar">
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm leading-5 font-medium">
                                                        {{ $user->full_name }}
                                                    </div>
                                                    <div class="text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                                                        {{ __('Registered on') }} <time datetime="{{ $user->created_at->format('Y-m-d') }}" class="capitalize">{{ $user->created_at->formatLocalized('%d %B %Y') }}</time>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-3 text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                                            <div class="flex items-center">
                                                @if($user->email_verified_at)
                                                    <x-heroicon-s-shield-check class="w-5 h-5 text-green-500" />
                                                @else
                                                    <x-heroicon-s-shield-exclamation class="w-5 h-5 text-red-500" />
                                                @endif
                                                <span class="ml-1.5">{{ $user->email }}</span>
                                            </div>
                                        </td>
                                        <td class="hidden md:table-cell px-6 py-3 whitespace-no-wrap text-sm leading-5 text-right">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-secondary-100 text-secondary-800 dark:bg-secondary-700 dark:text-secondary-400">
                                                {{ $user->roles_label }}
                                            </span>
                                        </td>
                                        <td class="hidden md:table-cell px-6 py-3 whitespace-no-wrap text-sm leading-5 text-secondary-500 dark:text-secondary-400 text-right">
                                            {{ $user->hasRole(config('shopper.system.users.admin_role')) ? __('Full') : __('Limited') }}
                                        </td>
                                        <td class="pr-6 text-right">
                                            @if($user->id === auth()->id())
                                                <span class="flex items-center text-sm leading-5 text-secondary-500 text-right dark:text-secondary-400">
                                                    <x-heroicon-o-user-circle class="w-5 h-5 mr-1" />
                                                    {{ __('Me') }}
                                                </span>
                                            @endif
                                            @if(auth()->user()->isAdmin() && ! $user->isAdmin())
                                                <x-shopper-dropdown customAlignmentClasses="right-12 -bottom-1">
                                                    <x-slot name="trigger">
                                                        <button id="admin-options-menu" aria-has-popup="true" :aria-expanded="open" type="button" class="w-8 h-8 inline-flex items-center justify-center text-secondary-400 rounded-full bg-transparent hover:text-secondary-500 focus:outline-none focus:text-secondary-500 focus:bg-secondary-100 dark:focus:bg-secondary-700 transition ease-in-out duration-150">
                                                            <x-heroicon-s-dots-vertical class="w-5 h-5" />
                                                        </button>
                                                    </x-slot>

                                                    <x-slot name="content">
                                                        <div class="py-1">
                                                            <button wire:click="removeUser({{ $user->id }})" type="button" class="group flex w-full items-center px-4 py-2 text-sm leading-5 text-secondary-700 dark:text-secondary-400 hover:bg-secondary-100 dark:hover:bg-secondary-600 hover:text-secondary-900 dark:hover:text-white" role="menuitem">
                                                                <x-heroicon-s-trash class="mr-3 h-5 w-5 text-secondary-400 group-hover:text-secondary-500" />
                                                                {{ __('Delete') }}
                                                            </button>
                                                        </div>
                                                    </x-slot>
                                                </x-shopper-dropdown>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="flex items-center justify-between px-4 py-3 sm:px-6 border-t border-secondary-200 rounded-b-md bg-white dark:bg-secondary-800 dark:border-secondary-700">
                        <div class="flex-1 flex justify-between sm:hidden">
                            {{ $users->links('shopper::livewire.wire-mobile-pagination-links') }}
                        </div>
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm leading-5 text-secondary-700 dark:text-secondary-400">
                                    {{ __('Showing') }}
                                    <span class="font-medium">{{ ($users->currentPage() - 1) * $users->perPage() + 1 }}</span>
                                    {{ __('to') }}
                                    <span class="font-medium">{{ ($users->currentPage() - 1) * $users->perPage() + count($users->items()) }}</span>
                                    {{ __('of') }}
                                    <span class="font-medium"> {!! $users->total() !!}</span>
                                    {{ __('results') }}
                                </p>
                            </div>
                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
