<x-shopper::container>
    <x-shopper::heading :title="__('shopper::pages/settings/staff.header_title')" />

    <div class="mt-10 divide-y divide-sh-border">
        <div class="pb-10">
            <div class="flex items-center gap-3">
                <h2 class="font-medium text-sh-fg">
                    {{ __('shopper::pages/settings/staff.role_available') }}
                </h2>

                {{ $this->createRoleAction }}
            </div>
            <p class="mt-2 max-w-3xl text-sm text-sh-fg-muted">
                {{ __('shopper::pages/settings/staff.role_available_summary') }}
            </p>
            <div class="mt-6 grid gap-5 sm:grid-cols-3 lg:grid-cols-4">
                @foreach ($roles as $role)
                    <x-shopper::card class="[&>header]:py-2 [&>header]:min-h-10">
                        <x-slot:title>
                            <div class="flex items-center justify-between">
                                <span
                                    class="text-xs leading-4 font-semibold tracking-wider text-sh-fg-muted uppercase"
                                >
                                    {{ $role->users->count() }}
                                    {{ \Illuminate\Support\Str::plural(__('shopper::words.account'), $role->users->count()) }}
                                </span>
                                <div class="ml-4 flex overflow-hidden">
                                    @foreach ($role->users as $admin)
                                        <img
                                            class="{{ $loop->first ? '' : '-ml-1' }} shadow-solid inline-block size-6 rounded-full object-cover"
                                            src="{{ $admin->picture }}"
                                            alt=""
                                        />
                                    @endforeach
                                </div>
                            </div>
                        </x-slot:title>
                        <div class="relative">
                            <h3 class="leading-6 font-medium text-sh-fg">
                                {{ $role->display_name }}
                            </h3>
                            <p
                                class="itemps-center text-primary-600 dark:text-primary-400 group-hover:text-primary-500 mt-1.5 inline-flex gap-2 text-xs"
                            >
                                {{ __('shopper::words.view_details') }}
                                <x-untitledui-arrow-narrow-right class="size-4" aria-hidden="true" />
                            </p>
                            <x-shopper::link :href="route('shopper.settings.users.role', $role)">
                                <span class="absolute inset-0"></span>
                            </x-shopper::link>
                        </div>
                    </x-shopper::card>
                @endforeach
            </div>
        </div>

        <div class="py-10 space-y-6">
            <div class="space-y-3 sm:flex sm:items-center sm:justify-between sm:space-y-0 sm:space-x-4">
                <div class="max-w-2xl min-w-0 flex-1">
                    <h2 class="font-medium text-sh-fg">
                        {{ __('shopper::pages/settings/staff.admin_accounts') }}
                    </h2>
                    <p class="mt-3 text-sm text-sh-fg-muted">
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

            <livewire:shopper-settings.team.administrators />
        </div>
    </div>

    <x-filament-actions::modals />
</x-shopper::container>
