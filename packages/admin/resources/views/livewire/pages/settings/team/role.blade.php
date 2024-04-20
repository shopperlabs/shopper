<div x-data="{
    options: ['role', 'users', 'permissions'],
    currentTab: 'role',
}" class="pb-10">
    <x-shopper::container>
        <x-shopper::breadcrumb :back="route('shopper.settings.users')" :current="$role->display_name">
            <x-untitledui-chevron-left class="h-4 w-4 shrink-0 text-gray-300 dark:text-gray-600" />
            <x-shopper::breadcrumb.link :link="route('shopper.settings.users')" :title="__('Staff & permissions')" />
        </x-shopper::breadcrumb>

        <x-shopper::heading class="my-6">
            <x-slot name="title">
                {{ $role->display_name }}
            </x-slot>

            <x-slot name="action">
                <div class="flex space-x-3">
                    {{ $this->deleteAction }}

                    <x-shopper::buttons.primary
                        wire:click="$dispatch(
                            'openModal',
                            {
                                component: 'shopper-modals.create-permission',
                                arguments: { 'id': {{  $role->id }} }
                             }
                        )"
                        type="button"
                    >
                        <x-untitledui-lock-04 class="mr-2 h-5 w-5" aria-hidden="true" />
                        {{ __('shopper::pages/settings.roles_permissions.create_permission') }}
                    </x-shopper::buttons.primary>
                </div>
            </x-slot>
        </x-shopper::heading>
    </x-shopper::container>

    <div class="relative border-t border-gray-200 dark:border-gray-700">
        <x-filament::tabs :contained="true">
            <x-filament::tabs.item alpine-active="currentTab === 'role'" x-on:click="currentTab = 'role'">
                {{ __('shopper::layout.forms.label.role') }}
            </x-filament::tabs.item>
            <x-filament::tabs.item alpine-active="currentTab === 'users'" x-on:click="currentTab = 'users'">
                {{ __('shopper::words.users') }}
            </x-filament::tabs.item>
            <x-filament::tabs.item alpine-active="currentTab === 'permissions'" x-on:click="currentTab = 'permissions'">
                {{ __('shopper::pages/settings.roles_permissions.permissions') }}
            </x-filament::tabs.item>
        </x-filament::tabs>
    </div>

    <div class="mt-10">
        <div x-show="currentTab === 'role'">
            <x-shopper::container>
                <div class="w-full space-y-6 lg:max-w-4xl">
                    @if (config('shopper.core.users.admin_role') === $role->name)
                        <div class="rounded-md bg-info-500 bg-opacity-10 p-4">
                            <div class="flex">
                                <div class="shrink-0">
                                    <x-untitledui-alert-circle class="h-5 w-5 text-info-400" aria-hidden="true" />
                                </div>
                                <div class="ml-3 flex-1 lg:flex lg:justify-between">
                                    <p class="text-sm leading-5 text-info-700">
                                        {{ __('shopper::pages/settings.roles_permissions.role_alert_msg') }}
                                    </p>
                                    <p class="mt-3 text-sm leading-5 lg:ml-6 lg:mt-0">
                                        <a
                                            href="https://laravelshopper.dev/roles-permissions"
                                            target="_blank"
                                            class="whitespace-no-wrap font-medium text-info-700 transition duration-150 ease-in-out hover:text-info-600"
                                        >
                                            {{ __('shopper::components.learn_more') }} &rarr;
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form wire:submit="save">
                        {{ $this->form }}

                        <div class="mt-5 text-right">
                            <x-shopper::buttons.primary type="submit" wire:loading.attr="disabled">
                                <x-shopper::loader wire:loading wire:target="save" class="text-white" />
                                {{ __('shopper::layout.forms.actions.update') }}
                            </x-shopper::buttons.primary>
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
