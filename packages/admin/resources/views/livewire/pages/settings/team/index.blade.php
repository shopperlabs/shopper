<x-shopper::container>
    <x-shopper::breadcrumb :back="route('shopper.settings.index')" :current="__('Staff & permissions')">
        <x-untitledui-chevron-left class="h-4 w-4 shrink-0 text-gray-300 dark:text-gray-600" aria-hidden="true" />
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
        <div class="space-y-6">
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

            {{ $this->table }}
        </div>
    </div>
</x-shopper::container>
