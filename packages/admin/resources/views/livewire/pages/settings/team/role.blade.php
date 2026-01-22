<div x-data="{
    options: ['role', 'users', 'permissions'],
    currentTab: 'role',
}" class="pb-10">
    <x-shopper::container>
        <x-shopper::breadcrumb :back="route('shopper.settings.users')" :current="$role->display_name">
            <x-untitledui-chevron-left class="size-4 shrink-0 text-gray-300 dark:text-gray-600" aria-hidden="true" />
            <x-shopper::breadcrumb.link
                :link="route('shopper.settings.users')"
                :title="__('shopper::pages/settings/menu.staff')"
            />
        </x-shopper::breadcrumb>

        <x-shopper::heading class="my-6" :title="$role->display_name">
            <x-slot name="action">
                <div class="flex space-x-3">
                    {{ $this->deleteAction }}

                    {{ $this->createPermissionAction }}
                </div>
            </x-slot>
        </x-shopper::heading>
    </x-shopper::container>

    <div class="relative border-t border-gray-200 dark:border-white/10">
        <x-filament::tabs :contained="true">
            <x-filament::tabs.item alpine-active="currentTab === 'role'" x-on:click="currentTab = 'role'">
                {{ __('shopper::forms.label.role') }}
            </x-filament::tabs.item>
            <x-filament::tabs.item alpine-active="currentTab === 'users'" x-on:click="currentTab = 'users'">
                {{ __('shopper::words.users') }}
            </x-filament::tabs.item>
            <x-filament::tabs.item alpine-active="currentTab === 'permissions'" x-on:click="currentTab = 'permissions'">
                {{ __('shopper::pages/settings/staff.permissions') }}
            </x-filament::tabs.item>
        </x-filament::tabs>
    </div>

    <div class="mt-10">
        <div x-show="currentTab === 'role'">
            <x-shopper::container>
                <div class="w-full space-y-6 lg:max-w-4xl">
                    @if (config('shopper.core.roles.admin') === $role->name)
                        <div
                            class="bg-info-100 ring-info-200 dark:bg-info-800/20 dark:ring-info-400/20 rounded-md p-4 ring-1"
                        >
                            <div class="flex">
                                <div class="shrink-0">
                                    <x-untitledui-alert-circle class="text-info-400 size-5" aria-hidden="true" />
                                </div>
                                <div class="ml-3 flex-1 lg:flex lg:justify-between">
                                    <p class="text-info-700 dark:text-info-400 text-sm">
                                        {{ __('shopper::pages/settings/staff.role_alert_msg') }}
                                    </p>
                                    <p class="mt-3 text-sm leading-5 lg:mt-0 lg:ml-6">
                                        <a
                                            href="https://docs.laravelshopper.dev/v2/acl"
                                            target="_blank"
                                            class="whitespace-no-wrap text-info-700 hover:text-info-600 font-medium transition duration-150 ease-in-out"
                                        >
                                            {{ __('shopper::words.learn_more') }}
                                            &rarr;
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form wire:submit="save">
                        {{ $this->form }}

                        <div class="mt-5 text-right">
                            <x-filament::button type="submit" wire:loading.attr="disabled">
                                <x-shopper::loader wire:loading wire:target="save" class="text-white" />
                                {{ __('shopper::forms.actions.update') }}
                            </x-filament::button>
                        </div>
                    </form>
                </div>
            </x-shopper::container>
        </div>
        <div x-cloak x-show="currentTab === 'users'">
            <livewire:shopper-settings.team.users :role="$role" />
        </div>
        <div x-cloak x-show="currentTab === 'permissions'">
            <livewire:shopper-settings.team.permissions :role="$role" />
        </div>
    </div>

    <x-filament-actions::modals />
</div>
