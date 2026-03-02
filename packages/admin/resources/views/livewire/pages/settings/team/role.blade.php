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
                    @if (config('shopper.admin.roles.admin') === $role->name)
                        <x-filament::callout
                            color="info"
                            icon="untitledui-alert-circle"
                            :description="__('shopper::pages/settings/staff.role_alert_msg')"
                        >
                            <x-slot name="footer">
                                <a
                                    href="https://docs.laravelshopper.dev/v2/acl"
                                    target="_blank"
                                    class="text-info-700 hover:text-info-600 dark:text-info-400 text-sm font-medium"
                                >
                                    {{ __('shopper::words.learn_more') }} &rarr;
                                </a>
                            </x-slot>
                        </x-filament::callout>
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
