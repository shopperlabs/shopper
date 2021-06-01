<div
    x-data="{
        options: ['role', 'users', 'permissions'],
        words: {'role': '{{ __("Role") }}', 'users': '{{ __("Users") }}', 'permissions': '{{ __("Permissions") }}'},
        currentTab: 'role'
    }"
>
    <x-shopper-breadcrumb back="shopper.settings.users">
        <x-heroicon-s-chevron-left class="flex-shrink-0 h-5 w-5 text-gray-400" />
        <x-shopper-breadcrumb-link :link="route('shopper.settings.users')" title="Users & roles" />
    </x-shopper-breadcrumb>

    <div class="mt-3 pb-5 relative border-b border-gray-200 space-y-4 sm:pb-0 dark:border-gray-700">
        <div class="space-y-3 md:flex md:items-center md:justify-between md:space-y-0">
            <h3 class="text-2xl font-bold leading-6 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate dark:text-white">
                {{ $display_name }}
            </h3>
            <div class="flex space-x-3 md:absolute md:top-3 md:right-0">
                <span class="shadow-sm rounded-md">
                    @if($role->can_be_removed)
                        <x-shopper-danger-button wire:click="$emit('openModal', 'shopper-modals.delete-role', {{ json_encode(['id' => $role->id]) }})" type="button">
                            <x-heroicon-o-trash class="w-5 h-5 -ml-1 mr-2" />
                            {{ __('Delete') }}
                        </x-shopper-danger-button>
                    @endif
                </span>
                <span class="shadow-sm rounded-md">
                    <x-shopper-button wire:click="$emit('openModal', 'shopper-modals.create-permission', {{ json_encode(['id' => $role->id]) }})" type="button">
                        <x-heroicon-o-key class="w-5 h-5 -ml-1 mr-2" />
                        {{ __('Create permission') }}
                    </x-shopper-button>
                </span>
            </div>
        </div>
        <div>
            <!-- Dropdown menu on small screens -->
            <div class="sm:hidden">
                <x-shopper-input.select x-model="currentTab" aria-label="{{ __('Selected tab') }}">
                    <template x-for="option in options" :key="option">
                        <option
                            x-bind:value="option"
                            x-text="words[option]"
                            x-bind:selected="option === currentTab"
                        ></option>
                    </template>
                </x-shopper-input.select>
            </div>
            <!-- Tabs at small breakpoint and up -->
            <div class="hidden sm:block">
                <nav class="-mb-px flex space-x-8">
                    <button @click="currentTab = 'role'" type="button" class="whitespace-no-wrap pb-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-500 dark:hover:border-gray-400 focus:outline-none" aria-current="page" :class="{ 'border-blue-500 text-blue-600 dark:text-blue-600 focus:text-blue-800 focus:border-blue-700 dark:focus:text-blue-800 dark:focus:border-blue-700': currentTab === 'role' }">
                        {{ __('Role') }}
                    </button>

                    <button @click="currentTab = 'users'" type="button" class="whitespace-no-wrap pb-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-500 dark:hover:border-gray-400 focus:outline-none" :class="{ 'border-blue-500 text-blue-600 dark:text-blue-600 focus:text-blue-800 focus:border-blue-700 dark:focus:text-blue-800 dark:focus:border-blue-700': currentTab === 'users' }">
                        {{ __('Users') }}
                    </button>

                    <button @click="currentTab = 'permissions'" type="button" class="whitespace-no-wrap pb-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-500 dark:hover:border-gray-400 focus:outline-none" :class="{ 'border-blue-500 text-blue-600 dark:text-blue-600 focus:text-blue-800 focus:border-blue-700 dark:focus:text-blue-800 dark:focus:border-blue-700': currentTab === 'permissions' }">
                        {{ __('Permissions') }}
                    </button>
                </nav>
            </div>
        </div>
    </div>

    <div class="mt-6 pb-10">
        <div x-show="currentTab === 'role'" class="bg-white shadow overflow-hidden sm:rounded-md dark:bg-gray-800">
            @if(config('shopper.system.users.admin_role') === $role->name)
                <div class="pt-5 px-4 sm:px-6">
                    <div class="rounded-md bg-blue-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <x-heroicon-s-information-circle  class="h-5 w-5 text-blue-400" />
                            </div>
                            <div class="ml-3 flex-1 md:flex md:justify-between">
                                <p class="text-sm leading-5 text-blue-700">
                                    {{ __('You are about to update the admin role, this could block your access to the dashboard.') }}
                                </p>
                                <p class="mt-3 text-sm leading-5 md:mt-0 md:ml-6">
                                    <a href="#" class="whitespace-no-wrap font-medium text-blue-700 hover:text-blue-600 transition ease-in-out duration-150">
                                        {{ __('Learn more') }} &rarr;
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="px-4 py-5 sm:p-6">
                <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                    <x-shopper-input.group label="Name (in lowercase)" for="name" class="sm:col-span-1" :error="$errors->first('name')" isRequired>
                        <x-shopper-input.text wire:model.lazy="name" type="text" id="name" placeholder="manager" />
                    </x-shopper-input.group>
                    <x-shopper-input.group label="Display name" for="display_name" class="sm:col-span-1">
                        <x-shopper-input.text wire:model.lazy="display_name" type="text" id="display_name" placeholder="Manager" />
                    </x-shopper-input.group>
                    <x-shopper-input.group label="Description" for="description" class="sm:col-span-2">
                        <x-shopper-input.textarea wire:model.lazy="description" id="description" />
                    </x-shopper-input.group>
                </div>
            </div>
            <div class="px-4 py-3 text-right sm:px-6">
                <x-shopper-button wire:click="save" wire:loading.attr="disabled" type="button">
                    <x-shopper-loader wire:loading wire:target="save" class="text-white" />
                    {{ __('Update') }}
                </x-shopper-button>
            </div>
        </div>
        <div x-cloak x-show="currentTab === 'users'">
            <livewire:shopper-settings.management.users-role :role="$role" />
        </div>
        <div x-cloak x-show="currentTab === 'permissions'">
            <livewire:shopper-settings.management.permissions :role="$role" />
        </div>
    </div>

</div>
