<div
    x-data="{
        options: ['role', 'users', 'permissions'],
        words: {
            'role': '{{ __('shopper::layout.forms.label.role') }}',
            'users': '{{ __('shopper::words.users') }}',
            'permissions': '{{ __('shopper::pages/settings.roles_permissions.permissions') }}'
        },
        currentTab: 'role',
        activeTab(tab) {
            return this.currentTab === tab;
        },
    }"
>
    <x-shopper::container>
        <x-shopper::breadcrumb :back="route('shopper.settings.users')" :current="$display_name">
            <x-untitledui-chevron-left class="shrink-0 h-4 w-4 text-secondary-300 dark:text-secondary-600" />
            <x-shopper::breadcrumb.link :link="route('shopper.settings.users')" :title="__('Staff & permissions')" />
        </x-shopper::breadcrumb>

        <x-shopper::heading class="mt-5 border-b-0">
            <x-slot name="title">
                {{ $display_name }}
            </x-slot>
            <x-slot name="action">
                <div class="flex space-x-3">
                    @if($role->can_be_removed)
                        <span class="shadow-sm rounded-md">
                            <x-shopper::buttons.danger wire:click="$emit('openModal', 'shopper-modals.delete-role', {{ json_encode(['id' => $role->id]) }})" type="button">
                                <x-untitledui-trash-03 class="w-5 h-5 mr-2" />
                                {{ __('shopper::layout.forms.actions.delete') }}
                            </x-shopper::buttons.danger>
                        </span>
                    @endif
                    <span class="shadow-sm rounded-md">
                        <x-shopper::buttons.primary wire:click="$emit('openModal', 'shopper-modals.create-permission', {{ json_encode(['id' => $role->id]) }})" type="button">
                            <x-untitledui-lock-04 class="w-5 h-5 -ml-1 mr-2" />
                            {{ __('shopper::pages/settings.roles_permissions.create_permission') }}
                        </x-shopper::buttons.primary>
                    </span>
                </div>
            </x-slot>
        </x-shopper::heading>
    </x-shopper::container>

    <div class="relative border-secondary-200 space-y-4 px-4 lg:pt-5 lg:pb-0 lg:border-y lg:px-0 dark:border-secondary-700">
        <div>
            <div class="lg:hidden">
                <x-shopper::forms.select x-model="currentTab" aria-label="{{ __('shopper::words.selected_tab') }}">
                    <template x-for="option in options" :key="option">
                        <option
                            x-bind:value="option"
                            x-text="words[option]"
                            x-bind:selected="option === currentTab"
                        ></option>
                    </template>
                </x-shopper::forms.select>
            </div>

            <div class="hidden lg:block">
                <nav class="-mb-px flex space-x-8 px-6">
                    <button
                        @click="currentTab = 'role'"
                        type="button"
                        class="px-1 pb-4 text-sm font-medium leading-5 whitespace-no-wrap border-b-2 focus:outline-none"
                        :class="activeTab('role') ? 'border-primary-600 text-primary-500' : 'border-transparent hover:border-secondary-300 dark:hover:border-secondary-400 text-secondary-500 hover:text-secondary-700 dark:text-secondary-400 dark:hover:text-secondary-500'"
                    >
                        {{ __('shopper::layout.forms.label.role') }}
                    </button>

                    <button
                        @click="currentTab = 'users'"
                        type="button"
                        class="px-1 pb-4 text-sm font-medium leading-5 whitespace-no-wrap border-b-2 focus:outline-none"
                        :class="activeTab('users') ? 'border-primary-600 text-primary-500' : 'border-transparent hover:border-secondary-300 dark:hover:border-secondary-400 text-secondary-500 hover:text-secondary-700 dark:text-secondary-400 dark:hover:text-secondary-500'"
                    >
                        {{ __('shopper::words.users') }}
                    </button>

                    <button
                        @click="currentTab = 'permissions'"
                        type="button"
                        class="px-1 pb-4 text-sm font-medium leading-5 whitespace-no-wrap border-b-2 focus:outline-none"
                        :class="activeTab('permissions') ? 'border-primary-600 text-primary-500' : 'border-transparent hover:border-secondary-300 dark:hover:border-secondary-400 text-secondary-500 hover:text-secondary-700 dark:text-secondary-400 dark:hover:text-secondary-500'"
                    >
                        {{ __('shopper::pages/settings.roles_permissions.permissions') }}
                    </button>
                </nav>
            </div>
        </div>
    </div>

    <div class="mt-6 pb-10">
        <div x-show="currentTab === 'role'">
            <x-shopper::container>
                <div class="space-y-6 w-full lg:max-w-4xl">
                    @if(config('shopper.core.users.admin_role') === $role->name)
                        <div class="rounded-md bg-info-500 bg-opacity-10 p-4">
                            <div class="flex">
                                <div class="shrink-0">
                                    <x-untitledui-alert-circle  class="h-5 w-5 text-info-400" />
                                </div>
                                <div class="ml-3 flex-1 lg:flex lg:justify-between">
                                    <p class="text-sm leading-5 text-info-700">
                                        {{ __('shopper::pages/settings.roles_permissions.role_alert_msg') }}
                                    </p>
                                    <p class="mt-3 text-sm leading-5 lg:mt-0 lg:ml-6">
                                        <a href="https://laravelshopper.dev/roles-permissions" target="_blank" class="whitespace-no-wrap font-medium text-info-700 hover:text-info-600 transition ease-in-out duration-150">
                                            {{ __('shopper::components.learn_more') }} &rarr;
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div>
                        <div>
                            <div class="grid gap-4 md:grid-cols-2 md:gap-6">
                                <x-shopper::forms.group :label="__('shopper::modals.roles.labels.name')" for="name" class="md:col-span-1" :error="$errors->first('name')" isRequired>
                                    <x-shopper::forms.input wire:model.defer="name" type="text" class="dark:bg-secondary-800 dark:border-transparent" id="name" placeholder="manager" />
                                </x-shopper::forms.group>
                                <x-shopper::forms.group :label="__('shopper::layout.forms.label.display_name')" for="display_name" class="md:col-span-1">
                                    <x-shopper::forms.input wire:model.defer="display_name" type="text" class="dark:bg-secondary-800 dark:border-transparent" id="display_name" placeholder="Manager" />
                                </x-shopper::forms.group>
                                <x-shopper::forms.group :label="__('shopper::layout.forms.label.description')" for="description" class="md:col-span-2">
                                    <x-shopper::forms.textarea wire:model.defer="description" id="description" class="dark:bg-secondary-800 dark:border-transparent" />
                                </x-shopper::forms.group>
                            </div>
                        </div>
                        <div class="mt-5 text-right">
                            <x-shopper::buttons.primary wire:click="save" wire:loading.attr="disabled" type="button">
                                <x-shopper::loader wire:loading wire:target="save" class="text-white" />
                                {{ __('shopper::layout.forms.actions.update') }}
                            </x-shopper::buttons.primary>
                        </div>
                    </div>
                </div>
            </x-shopper::container>
        </div>
        <div x-cloak x-show="currentTab === 'users'">
            <livewire:shopper-settings.management.users-role :role="$role" />
        </div>
        <div x-cloak x-show="currentTab === 'permissions'">
            <livewire:shopper-settings.management.permissions :role="$role" />
        </div>
    </div>

</div>
