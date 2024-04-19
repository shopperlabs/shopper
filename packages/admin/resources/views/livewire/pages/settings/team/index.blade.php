<x-shopper::container>
    <x-shopper::breadcrumb :back="route('shopper.settings.index')" :current="__('Staff & permissions')">
        <x-untitledui-chevron-left class="h-4 w-4 shrink-0 text-gray-300 dark:text-gray-600" />
        <x-shopper::breadcrumb.link :link="route('shopper.settings.index')" :title="__('shopper::words.settings')" />
    </x-shopper::breadcrumb>

    <x-shopper::heading class="my-6">
        <x-slot name="title">
            {{ __('shopper::pages/settings.roles_permissions.header_title') }}
        </x-slot>
    </x-shopper::heading>

    <div class="mt-10 space-y-12 pb-10">
        <div>
            <div class="flex items-center">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white">
                    {{ __('shopper::pages/settings.roles_permissions.role_available') }}
                </h2>
                <button
                    wire:click="$dispatch(
                        'openModal',
                        { component: 'shopper-modals.create-role' }
                    )"
                    type="button"
                    class="focus:shadow-outline-primary ml-3 inline-flex items-center rounded-lg border border-transparent bg-primary-100 px-2.5 py-1.5 text-sm font-medium leading-4 text-primary-700 transition duration-150 ease-in-out hover:bg-primary-50 focus:border-primary-300 focus:outline-none active:bg-primary-200"
                >
                    <x-untitledui-plus class="mr-2 h-5 w-5" aria-hidden="true" />
                    {{ __('shopper::pages/settings.roles_permissions.new_role') }}
                </button>
            </div>
            <p class="mt-2 text-gray-500 dark:text-gray-400">
                {{ __('shopper::pages/settings.roles_permissions.role_available_summary') }}
            </p>
            <div class="mt-6 grid gap-5 sm:grid-cols-3 lg:grid-cols-4">
                @foreach ($roles as $role)
                    <x-shopper::link
                        href="{{ route('shopper.settings.users.role', $role) }}"
                        class="group flex flex-col justify-between overflow-hidden rounded-lg p-4 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:ring-gray-700"
                    >
                        <div class="flex items-center justify-between">
                            <span
                                class="text-xs font-semibold uppercase leading-4 tracking-wider text-gray-400 dark:text-gray-500"
                            >
                                {{ $role->users->count() }}
                                {{ \Illuminate\Support\Str::plural(__('shopper::words.account'), $role->users->count()) }}
                            </span>
                            <div class="ml-4 flex overflow-hidden">
                                @foreach ($role->users as $admin)
                                    <img
                                        class="{{ $loop->first ? '' : '-ml-1' }} shadow-solid inline-block h-6 w-6 rounded-full"
                                        src="{{ $admin->picture }}"
                                        alt=""
                                    />
                                @endforeach
                            </div>
                        </div>
                        <div>
                            <h3 class="mt-4 text-lg font-medium leading-6 text-gray-900 dark:text-white">
                                {{ $role->display_name }}
                            </h3>
                            <p class="mt-1 flex items-center text-sm text-primary-600 group-hover:text-primary-500">
                                {{ __('shopper::words.view_details') }}
                                <x-untitledui-arrow-narrow-right class="ml-2 h-5 w-5" aria-hidden="true" />
                            </p>
                        </div>
                    </x-shopper::link>
                @endforeach
            </div>
        </div>
        <div>
            <div
                class="space-y-3 border-b border-gray-200 pb-6 dark:border-gray-700 sm:flex sm:items-center sm:justify-between sm:space-x-4 sm:space-y-0"
            >
                <div class="min-w-0 max-w-2xl flex-1">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white">
                        {{ __('shopper::pages/settings.roles_permissions.admin_accounts') }}
                    </h2>
                    <p class="mt-3 text-gray-500 dark:text-gray-400">
                        {{ __('shopper::pages/settings.roles_permissions.admin_accounts_summary') }}
                    </p>
                </div>
                <div>
                    <x-shopper::buttons.primary
                        wire:click="$dispatch('openPanel', {
                            component: 'shopper-slide-overs.create-team-member'
                        })"
                    >
                        <x-untitledui-user-plus class="mr-2 h-5 w-5" aria-hidden="true" />
                        {{ __('shopper::pages/settings.roles_permissions.add_admin') }}
                    </x-shopper::buttons.primary>
                </div>
            </div>
            <div class="mt-6 overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="overflow-x-auto">
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
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @foreach ($users as $user)
                                    <tr>
                                        <td
                                            class="whitespace-no-wrap px-6 py-3 text-sm font-medium leading-5 text-gray-900 dark:text-white"
                                        >
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
                                        </td>
                                        <td class="px-6 py-3 text-sm leading-5 text-gray-500 dark:text-gray-400">
                                            <div class="flex items-center">
                                                @if ($user->email_verified_at)
                                                    <x-untitledui-check-verified-02
                                                        class="h-5 w-5 text-green-500"
                                                        aria-hidden="true"
                                                    />
                                                @else
                                                    <x-untitledui-alert-circle
                                                        class="h-5 w-5 text-danger-500"
                                                        aria-hidden="true"
                                                    />
                                                @endif
                                                <span class="ml-1.5">{{ $user->email }}</span>
                                            </div>
                                        </td>
                                        <td
                                            class="whitespace-no-wrap hidden px-6 py-3 text-right text-sm leading-5 lg:table-cell"
                                        >
                                            <x-shopper::badge :value="$user->roles_label" />
                                        </td>
                                        <td
                                            class="whitespace-no-wrap hidden px-6 py-3 text-right text-sm leading-5 text-gray-500 dark:text-gray-400 lg:table-cell"
                                        >
                                            {{
                                                $user->hasRole(config('shopper.core.users.admin_role'))
                                                    ? __('shopper::words.full')
                                                    : __('shopper::words.limited')
                                            }}
                                        </td>
                                        <td class="pr-6 text-right">
                                            @if ($user->id === auth()->id())
                                                <span
                                                    class="flex items-center text-right text-sm leading-5 text-gray-500 dark:text-gray-400"
                                                >
                                                    <x-untitledui-user-circle class="mr-2 h-5 w-5" aria-hidden="true" />
                                                    {{ __('shopper::words.me') }}
                                                </span>
                                            @endif

                                            @if (auth()->user()->isAdmin() && ! $user->isAdmin())
                                                <button
                                                    wire:click="removeUser({{ $user->id }})"
                                                    wire:confirm="Are you sure you want to delete this member?"
                                                    type="button"
                                                    class="flex items-center rounded-full p-2 text-right text-sm text-gray-500 hover:bg-gray-50 dark:text-gray-400 dark:hover:bg-gray-700"
                                                >
                                                    <x-untitledui-trash-03 class="h-5 w-5" aria-hidden="true" />
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div
                        class="flex items-center justify-between rounded-b-lg border-t border-gray-200 px-4 py-3 dark:border-gray-700 sm:px-6"
                    >
                        <div class="flex flex-1 justify-between sm:hidden">
                            {{ $users->links('shopper::livewire.wire-mobile-pagination-links') }}
                        </div>
                        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm leading-5 text-gray-700 dark:text-gray-400">
                                    {{ __('shopper::words.showing') }}
                                    <span class="font-medium">
                                        {{ ($users->currentPage() - 1) * $users->perPage() + 1 }}
                                    </span>
                                    {{ __('shopper::words.to') }}
                                    <span class="font-medium">
                                        {{ ($users->currentPage() - 1) * $users->perPage() + count($users->items()) }}
                                    </span>
                                    {{ __('shopper::words.of') }}
                                    <span class="font-medium">{!! $users->total() !!}</span>
                                    {{ __('shopper::words.results') }}
                                </p>
                            </div>
                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-shopper::container>
