<div
    x-data="{
        options: ['role', 'users', 'permissions'],
        words: {
            'role': '{{ __('shopper::layout.forms.label.role') }}',
            'users': '{{ __('shopper::words.users') }}',
            'permissions': '{{ __('shopper::pages/settings.roles_permissions.permissions') }}'
        },
        currentTab: 'role'
    }"
>
    <x-shopper::container>
        <x-shopper::breadcrumb :back="route('shopper.settings.users')" :current="$display_name">
            <x-heroicon-s-chevron-left class="shrink-0 h-4 w-4 text-secondary-300 dark:text-secondary-600" />
            <x-shopper::breadcrumb.link :link="route('shopper.settings.users')" :title="__('Staff & permissions')" />
        </x-shopper::breadcrumb>

        <x-shopper::heading class="border-b-0">
            <x-slot name="title">
                {{ $display_name }}
            </x-slot>
            <x-slot name="action">
                <div class="flex space-x-3">
                    @if($role->can_be_removed)
                        <span class="shadow-sm rounded-md">
                            <x-shopper::buttons.danger wire:click="$emit('openModal', 'shopper-modals.delete-role', {{ json_encode(['id' => $role->id]) }})" type="button">
                                <x-heroicon-o-trash class="w-5 h-5 -ml-1 mr-2" />
                                {{ __('shopper::layout.forms.actions.delete') }}
                            </x-shopper::buttons.danger>
                        </span>
                    @endif
                    <span class="shadow-sm rounded-md">
                        <x-shopper::buttons.primary wire:click="$emit('openModal', 'shopper-modals.create-permission', {{ json_encode(['id' => $role->id]) }})" type="button">
                            <x-heroicon-o-key class="w-5 h-5 -ml-1 mr-2" />
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
                    <button @click="currentTab = 'role'" type="button" class="whitespace-no-wrap pb-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-secondary-500 hover:text-secondary-700 hover:border-secondary-300 dark:text-secondary-400 dark:hover:text-secondary-500 dark:hover:border-secondary-400 focus:outline-none" aria-current="page" :class="{ 'border-primary-500 text-primary-600 focus:text-primary-800 focus:border-primary-700': currentTab === 'role' }">
                        {{ __('shopper::layout.forms.label.role') }}
                    </button>

                    <button @click="currentTab = 'users'" type="button" class="whitespace-no-wrap pb-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-secondary-500 hover:text-secondary-700 hover:border-secondary-300 dark:text-secondary-400 dark:hover:text-secondary-500 dark:hover:border-secondary-400 focus:outline-none" :class="{ 'border-primary-500 text-primary-600 focus:text-primary-800 focus:border-primary-700': currentTab === 'users' }">
                        {{ __('shopper::words.users') }}
                    </button>

                    <button @click="currentTab = 'permissions'" type="button" class="whitespace-no-wrap pb-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-secondary-500 hover:text-secondary-700 hover:border-secondary-300 dark:text-secondary-400 dark:hover:text-secondary-500 dark:hover:border-secondary-400 focus:outline-none" :class="{ 'border-primary-500 text-primary-600 focus:text-primary-800 focus:border-primary-700': currentTab === 'permissions' }">
                        {{ __('shopper::pages/settings.roles_permissions.permissions') }}
                    </button>
                </nav>
            </div>
        </div>
    </div>

    <div class="mt-6 pb-10">
        <div x-show="currentTab === 'role'">
            <x-shopper::container>
                <div class="bg-white ring-1 ring-secondary-200 dark:ring-secondary-700 overflow-hidden rounded-lg dark:bg-secondary-800">
                    @if(config('shopper.core.users.admin_role') === $role->name)
                        <div class="pt-5 px-4 md:px-6">
                            <div class="rounded-md bg-info-500 bg-opacity-10 p-4">
                                <div class="flex">
                                    <div class="shrink-0">
                                        <x-heroicon-s-information-circle  class="h-5 w-5 text-info-400" />
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
                        </div>
                    @endif
                    <div class="px-4 py-5 lg:p-6">
                        <div class="grid gap-4 md:grid-cols-2 md:gap-6">
                            <x-shopper::forms.group :label="__('shopper::modals.roles.labels.name')" for="name" class="md:col-span-1" :error="$errors->first('name')" isRequired>
                                <x-shopper::forms.input wire:model.lazy="name" type="text" id="name" placeholder="manager" />
                            </x-shopper::forms.group>
                            <x-shopper::forms.group :label="__('shopper::layout.forms.label.display_name')" for="display_name" class="md:col-span-1">
                                <x-shopper::forms.input wire:model.lazy="display_name" type="text" id="display_name" placeholder="Manager" />
                            </x-shopper::forms.group>
                            <x-shopper::forms.group :label="__('shopper::layout.forms.label.description')" for="description" class="md:col-span-2">
                                <x-shopper::forms.textarea wire:model.lazy="description" id="description" />
                            </x-shopper::forms.group>
                        </div>
                    </div>
                    <div class="px-4 py-3 text-right md:px-6">
                        <x-shopper::buttons.primary wire:click="save" wire:loading.attr="disabled" type="button">
                            <x-shopper::loader wire:loading wire:target="save" class="text-white" />
                            {{ __('shopper::layout.forms.actions.update') }}
                        </x-shopper::buttons.primary>
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
