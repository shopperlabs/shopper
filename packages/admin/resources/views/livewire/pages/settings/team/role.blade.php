<div class="pb-10">
    <x-shopper::container>
        <x-shopper::heading class="my-6" :title="$role->display_name">
            <x-slot name="action">
                <div class="flex space-x-3">
                    {{ $this->deleteAction }}

                    {{ $this->generatePermissionsAction }}

                    {{ $this->createPermissionAction }}
                </div>
            </x-slot>
        </x-shopper::heading>
    </x-shopper::container>

    <div class="mt-10">
        <x-filament::tabs class="sh-tabs-underline">
            <x-filament::tabs.item
                :active="$activeTab === 'role'"
                wire:click="$set('activeTab', 'role')"
            >
                {{ __('shopper::forms.label.role') }}
            </x-filament::tabs.item>
            <x-filament::tabs.item
                :active="$activeTab === 'users'"
                wire:click="$set('activeTab', 'users')"
            >
                {{ __('shopper::words.users') }}
            </x-filament::tabs.item>
            <x-filament::tabs.item
                :active="$activeTab === 'permissions'"
                wire:click="$set('activeTab', 'permissions')"
            >
                {{ __('shopper::pages/settings/staff.permissions') }}
            </x-filament::tabs.item>
        </x-filament::tabs>
    </div>

    <div class="mt-10">
        <div @class(['hidden' => $activeTab !== 'role'])>
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
        <x-shopper::container @class(['hidden' => $activeTab !== 'users'])>
            <livewire:shopper-settings.team.administrators :$role />
        </x-shopper::container>
        <div @class(['hidden' => $activeTab !== 'permissions'])>
            <livewire:shopper-settings.team.permissions :$role />
        </div>
    </div>

    <x-filament-actions::modals />
</div>
