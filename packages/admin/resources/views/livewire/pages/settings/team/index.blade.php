<x-shopper::container>
    <x-shopper::breadcrumb
        :back="route('shopper.settings.index')"
        :current="__('shopper::pages/settings/menu.staff')"
    >
        <x-untitledui-chevron-left class="size-4 shrink-0 text-gray-300 dark:text-gray-600" aria-hidden="true" />
        <x-shopper::breadcrumb.link
            :link="route('shopper.settings.index')"
            :title="__('shopper::pages/settings/global.menu')"
        />
    </x-shopper::breadcrumb>

    <x-shopper::heading class="my-6" :title="__('shopper::pages/settings/staff.header_title')" />

    <div class="mt-10 space-y-12 pb-10">
        <div>
            <div class="flex items-center gap-3">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white">
                    {{ __('shopper::pages/settings/staff.role_available') }}
                </h2>

                {{ $this->createRoleAction }}
            </div>
            <p class="mt-2 text-gray-500 dark:text-gray-400">
                {{ __('shopper::pages/settings/staff.role_available_summary') }}
            </p>
            <div class="mt-6 grid gap-5 sm:grid-cols-3 lg:grid-cols-4">
                @foreach ($roles as $role)
                    <x-shopper::link
                        :href="route('shopper.settings.users.role', $role)"
                        class="group flex flex-col justify-between overflow-hidden rounded-xl bg-white p-4 shadow ring-1 ring-gray-200 dark:bg-gray-900 dark:ring-white/10"
                    >
                        <div class="flex items-center justify-between">
                            <span
                                class="text-xs leading-4 font-semibold tracking-wider text-gray-400 uppercase dark:text-gray-500"
                            >
                                {{ $role->users->count() }}
                                {{ \Illuminate\Support\Str::plural(__('shopper::words.account'), $role->users->count()) }}
                            </span>
                            <div class="ml-4 flex overflow-hidden">
                                @foreach ($role->users as $admin)
                                    <img
                                        class="{{ $loop->first ? '' : '-ml-1' }} shadow-solid inline-block size-6 rounded-full"
                                        src="{{ $admin->picture }}"
                                        alt=""
                                    />
                                @endforeach
                            </div>
                        </div>
                        <div>
                            <h3 class="mt-4 text-lg leading-6 font-medium text-gray-900 dark:text-white">
                                {{ $role->display_name }}
                            </h3>
                            <p class="inline-flex itemps-center gap-2 mt-1.5 text-xs text-primary-600 group-hover:text-primary-500">
                                {{ __('shopper::words.view_details') }}
                                <x-untitledui-arrow-narrow-right class="size-4" aria-hidden="true" />
                            </p>
                        </div>
                    </x-shopper::link>
                @endforeach
            </div>
        </div>
        <div class="space-y-6">
            <div
                class="space-y-3 border-b border-gray-200 pb-6 sm:flex sm:items-center sm:justify-between sm:space-y-0 sm:space-x-4 dark:border-white/10"
            >
                <div class="max-w-2xl min-w-0 flex-1">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white">
                        {{ __('shopper::pages/settings/staff.admin_accounts') }}
                    </h2>
                    <p class="mt-3 text-gray-500 dark:text-gray-400">
                        {{ __('shopper::pages/settings/staff.admin_accounts_summary') }}
                    </p>
                </div>
                <div>
                    <x-filament::button
                        wire:click="$dispatch('openPanel', {
                            component: 'shopper-slide-overs.create-team-member'
                        })"
                    >
                        <x-untitledui-user-plus class="mr-2 size-5" aria-hidden="true" />
                        {{ __('shopper::pages/settings/staff.add_admin') }}
                    </x-filament::button>
                </div>
            </div>

            {{ $this->table }}
        </div>
    </div>

    <x-filament-actions::modals />
</x-shopper::container>
