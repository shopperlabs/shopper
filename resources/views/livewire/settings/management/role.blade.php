<div
    x-data="{
        options: ['role', 'users', 'permissions'],
        words: {'role': '{{ __("Role") }}', 'users': '{{ __("Users") }}', 'permissions': '{{ __("Permissions") }}'},
        currentTab: 'role'
    }"
>
    <x-shopper-breadcrumb back="shopper.settings.users">
        <svg class="flex-shrink-0 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
        </svg>
        <a href="{{ route('shopper.settings.users') }}" class="text-gray-500 hover:text-gray-700 focus:outline-none focus:underline transition duration-150 ease-in-out">{{ __('Users & roles') }}</a>
    </x-shopper-breadcrumb>

    <div class="mt-3 relative pb-5 border-b border-gray-200 space-y-4 sm:pb-0">
        <div class="space-y-3 md:flex md:items-center md:justify-between md:space-y-0">
            <h3 class="text-2xl font-bold leading-6 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
                {{ $display_name }}
            </h3>
            <div class="flex space-x-3 md:absolute md:top-3 md:right-0">
                <span class="shadow-sm rounded-md">
                    @if($role->can_be_removed)
                        <x-shopper-danger-button wire:click="$emit('openModal', 'shopper-modals.delete-role', {{ json_encode(['id' => $role->id]) }})" type="button">
                            <svg class="w-5 h-5 -ml-1 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            {{ __('Delete') }}
                        </x-shopper-danger-button>
                    @endif
                </span>
                <span class="shadow-sm rounded-md">
                    <x-shopper-button wire:click="$emit('openModal', 'shopper-modals.create-permission', {{ json_encode(['id' => $role->id]) }})" type="button">
                        <svg class="w-5 h-5 -ml-1 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                        </svg>
                        {{ __('Create permission') }}
                    </x-shopper-button>
                </span>
            </div>
        </div>
        <div>
            <!-- Dropdown menu on small screens -->
            <div class="sm:hidden">
                <select x-model="currentTab" aria-label="Selected tab" class="form-select block w-full pl-3 pr-10 py-2 text-base leading-6 border-gray-300 focus:outline-none focus:shadow-outline-blue focus:border-blue-300 sm:text-sm sm:leading-5 transition ease-in-out duration-150">
                    <template x-for="option in options" :key="option">
                        <option
                            x-bind:value="option"
                            x-text="words[option]"
                            x-bind:selected="option === currentTab"
                        ></option>
                    </template>
                </select>
            </div>
            <!-- Tabs at small breakpoint and up -->
            <div class="hidden sm:block">
                <nav class="-mb-px flex space-x-8">
                    <button @click="currentTab = 'role'" type="button" class="whitespace-no-wrap pb-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300" aria-current="page" :class="{ 'border-blue-500 text-blue-600 focus:text-blue-800 focus:border-blue-700': currentTab === 'role' }">
                        {{ __('Role') }}
                    </button>

                    <button @click="currentTab = 'users'" type="button" class="whitespace-no-wrap pb-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300" :class="{ 'border-blue-500 text-blue-600 focus:text-blue-800 focus:border-blue-700': currentTab === 'users' }">
                        {{ __('Users') }}
                    </button>

                    <button @click="currentTab = 'permissions'" type="button" class="whitespace-no-wrap pb-4 px-1 border-b-2 border-transparent font-medium text-sm leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300" :class="{ 'border-blue-500 text-blue-600 focus:text-blue-800 focus:border-blue-700': currentTab === 'permissions' }">
                        {{ __('Permissions') }}
                    </button>
                </nav>
            </div>
        </div>
    </div>

    <div class="mt-6 pb-10">
        <div x-show="currentTab === 'role'" class="bg-white shadow overflow-hidden sm:rounded-md">
            @if(config('shopper.system.users.admin_role') === $role->name)
                <div class="pt-5 px-4 sm:px-6">
                    <div class="rounded-md bg-blue-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
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
            <div class="px-4 py-5 bg-white sm:p-6">
                <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                    <div class="sm:col-span-1">
                        <x-shopper-input.group label="Name (in lowercase)" for="name" isRequired :error="$errors->first('name')">
                            <x-shopper-input.text wire:model.lazy="name" id="name" placeholder="manager" />
                        </x-shopper-input.group>
                    </div>
                    <div class="sm:col-span-1">
                        <x-shopper-input.group label="Display name" for="display_name">
                            <x-shopper-input.text wire:model.lazy="display_name" id="display_name" placeholder="Manager" />
                        </x-shopper-input.group>
                    </div>
                    <div class="sm:col-span-2">
                        <x-shopper-input.group label="Description" for="description">
                            <x-shopper-input.textarea wire:model.lazy="description" id="description" />
                        </x-shopper-input.group>
                    </div>
                </div>
            </div>
            <div class="px-4 py-3 text-right sm:px-6">
                <x-shopper-button wire:click="save" wire:loading.attr="disabled" type="button">
                    <x-shopper-loader wire:loading wire:target="save" class="text-white" />
                    {{ __("Update") }}
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
