<div x-data="{ show: false }">
    <x:shopper-breadcrumb back="shopper.settings.index">
        <svg class="flex-shrink-0 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
        </svg>
        <a href="{{ route('shopper.settings.index') }}" class="text-gray-500 hover:text-gray-700 focus:outline-none focus:underline transition duration-150 ease-in-out">{{ __('Settings') }}</a>
    </x:shopper-breadcrumb>

    <div class="mt-2 md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-6 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate pb-5 border-b border-gray-200">{{ __('Administrators & roles') }}</h2>
        </div>
    </div>

    <div class="mt-8 space-y-10">
        <div class="bg-white rounded-lg p-4 sm:p-6 shadow-md overflow-hidden">
            <div class="flex items-center">
                <h2 class="text-lg leading-6 font-medium text-gray-900">{{ __("Administrator role available") }}</h2>
                <button @click="show = true" type="button" class="ml-3 inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs leading-4 font-medium rounded text-blue-700 bg-blue-100 hover:bg-blue-50 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-blue-200 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    {{ __("Add new role") }}
                </button>
            </div>
            <p class="mt-3 text-gray-500 leading-6 text-base">
                {{ __('A role provides access to predefined menus and features so that depending on the assigned role and permissions an administrator can have access to what he needs.') }}
            </p>
            <div class="mt-8 grid gap-4 sm:grid-cols-2 sm:gap-6 md:grid-cols-3 md:gap-8">
                @foreach($roles as $role)
                    <a href="{{ route('shopper.settings.user.role', $role) }}" class="group flex flex-col justify-between p-4 border border-gray-200 rounded-md overflow-hidden hover:shadow-md focus:border-gray-300 focus:outline-none transition duration-200 ease-in-out">
                        <div class="flex items-center justify-between">
                            <span class="text-xs leading-4 text-gray-400 font-semibold uppercase tracking-wider">{{ $role->users->count() }} {{ str_plural(__("Account"), $role->users->count()) }}</span>
                            <div class="flex overflow-hidden ml-4">
                                @foreach($role->users as $admin)
                                    <img class="{{ $loop->first ? '' : '-ml-1' }} inline-block h-6 w-6 rounded-full text-white shadow-solid" src="{{ $admin->picture }}" alt="">
                                @endforeach
                            </div>
                        </div>
                        <div>
                            <h3 class="mt-4 text-gray-900 text-lg leading-6 font-medium">{{ $role->display_name }}</h3>
                            <p class="mt-1 flex items-center text-sm text-blue-600 group-hover:text-blue-500 transition duration-200 ease-in-out">
                                {{ __("View details") }}
                                <span class="ml-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                    </svg>
                                </span>
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
        <div class="bg-white rounded-lg p-4 sm:p-6 shadow-md">
            <div class="pb-6 border-b border-gray-200 space-y-3 sm:flex sm:items-center sm:justify-between sm:space-x-4 sm:space-y-0">
                <div class="flex-1 min-w-0 max-w-2xl">
                    <h2 class="text-lg leading-6 font-medium text-gray-900">{{ __("Administrators accounts") }}</h2>
                    <p class="mt-3 text-gray-500 leading-6 text-base">
                        {{ __("These are the members who are already in your store with their associated roles. You can assign new roles to existing member here.") }}
                    </p>
                </div>
                <div>
                    <a href="{{ route('shopper.settings.user.new') }}" class="inline-flex items-center px-4 py-2 shadow-sm rounded-md border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:shadow-outline-blue focus:border-blue-700 active:bg-blue-700 transition duration-150 ease-in-out">
                        <svg class="w-5 h-5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        {{ __("Add administrator") }}
                    </a>
                </div>
            </div>
            <div class="mt-6 rounded-md border border-gray-200">
                <div class="overflow-x-auto">
                    <div class="align-middle inline-block min-w-full">
                        <table class="min-w-full">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider rounded-t-md">
                                        <span class="lg:pl-2">{{ __("Name") }}</span>
                                    </th>
                                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __("Email Address") }}
                                    </th>
                                    <th class="hidden md:table-cell px-6 py-3 border-b border-gray-200 bg-gray-50 text-right text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __("Role") }}
                                    </th>
                                    <th class="hidden md:table-cell px-6 py-3 border-b border-gray-200 bg-gray-50 text-right text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                                        {{ __("Access") }}
                                    </th>
                                    <th class="pr-6 py-3 border-b border-gray-200 bg-gray-50 text-right text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider rounded-t-md"></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100" x-max="1">
                                @foreach($users as $user)
                                    <tr>
                                        <td class="px-6 py-3 whitespace-no-wrap text-sm leading-5 font-medium text-gray-900">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <img class="h-10 w-10 rounded-full" src="{{ $user->picture }}" alt="">
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm leading-5 font-medium text-gray-900">
                                                        {{ $user->full_name }}
                                                    </div>
                                                    <div class="text-sm leading-5 text-gray-500 font-normal">
                                                        {{ __("Registered on") }} <time datetime="{{ $user->created_at->format('Y-m-d') }}" class="capitalize">{{ $user->created_at->formatLocalized('%d %B %Y') }}</time>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-3 text-sm leading-5 text-gray-500">
                                            <div class="flex items-center">
                                                @if($user->email_verified_at)
                                                    <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                    </svg>
                                                @else
                                                    <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 1.944A11.954 11.954 0 012.166 5C2.056 5.649 2 6.319 2 7c0 5.225 3.34 9.67 8 11.317C14.66 16.67 18 12.225 18 7c0-.682-.057-1.35-.166-2.001A11.954 11.954 0 0110 1.944zM11 14a1 1 0 11-2 0 1 1 0 012 0zm0-7a1 1 0 10-2 0v3a1 1 0 102 0V7z" clip-rule="evenodd" />
                                                    </svg>
                                                @endif
                                                <span class="ml-1.5">{{ $user->email }}</span>
                                            </div>
                                        </td>
                                        <td class="hidden md:table-cell px-6 py-3 whitespace-no-wrap text-sm leading-5 text-gray-500 text-right">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                {{ $user->roles_label }}
                                            </span>
                                        </td>
                                        <td class="hidden md:table-cell px-6 py-3 whitespace-no-wrap text-sm leading-5 text-gray-500 text-right">
                                            {{ $user->hasRole(config('shopper.system.users.admin_role')) ? __("Full") : __("Limited") }}
                                        </td>
                                        <td class="pr-6 text-right">
                                            @if($user->id === auth()->id())
                                                <span class="flex items-center text-sm text-gray-500 leading-5 text-right">
                                                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    {{ __("Me") }}
                                                </span>
                                            @endif
                                            @if(auth()->user()->isAdmin() && !$user->isAdmin())
                                                <div x-data="{ open: false }" x-on:user-removed.window="open = false" @keydown.escape="open = false" @click.away="open = false" class="relative flex justify-end items-center">
                                                    <button id="project-options-menu-0" aria-has-popup="true" :aria-expanded="open" type="button" @click="open = !open" class="w-8 h-8 inline-flex items-center justify-center text-gray-400 rounded-full bg-transparent hover:text-gray-500 focus:outline-none focus:text-gray-500 focus:bg-gray-100 transition ease-in-out duration-150">
                                                        <svg class="w-5 h-5" x-description="Heroicon name: dots-vertical" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                                        </svg>
                                                    </button>
                                                    <div x-show="open" x-description="Dropdown panel, show/hide based on dropdown state." x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="mx-3 origin-top-right absolute right-7 top-0 w-48 mt-1 rounded-md shadow-lg" style="display: none;">
                                                        <div class="z-10 rounded-md bg-white shadow-xs" role="menu" aria-orientation="vertical" aria-labelledby="project-options-menu-0">
                                                            <div class="py-1">
                                                                <button wire:click="removeUser({{ $user->id }})" type="button" class="group w-full flex items-center px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900" role="menuitem">
                                                                    <svg class="mr-3 h-5 w-5 text-gray-400 group-hover:text-gray-500 group-focus:text-gray-500" x-description="Heroicon name: trash" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                                    </svg>
                                                                    {{ __("Delete") }}
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="rounded-b-md bg-white px-4 py-3 flex items-center justify-between sm:px-6 border-t border-gray-200">
                        <div class="flex-1 flex justify-between sm:hidden">
                            {{ $users->links('shopper::livewire.wire-mobile-pagination-links') }}
                        </div>
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm leading-5 text-gray-700">
                                    {{ __('Showing') }}
                                    <span class="font-medium">{{ ($users->currentPage() - 1) * $users->perPage() + 1 }}</span>
                                    {{ __('to') }}
                                    <span class="font-medium">{{ ($users->currentPage() - 1) * $users->perPage() + count($users->items()) }}</span>
                                    {{ __('of') }}
                                    <span class="font-medium"> {!! $users->total() !!}</span>
                                    {{ __('results') }}
                                </p>
                            </div>
                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div
        x-cloak
        x-show="show"
        class="fixed bottom-0 z-50 inset-x-0 px-4 pb-4 sm:inset-0 sm:flex sm:items-center sm:justify-center"
        x-on:role-added.window="show = false"
    >
        <div
            x-cloak
            x-show="show"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 transition-opacity"
        >
            <div class="absolute inset-0 bg-gray-700 opacity-75"></div>
        </div>
        <div
            x-cloak
            x-show="show"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="bg-white rounded-md overflow-hidden shadow-xl transform transition-all sm:max-w-2xl sm:w-full"
        >
            <div class="bg-white">
                <div class="sm:flex sm:items-start px-4 sm:px-6 py-4">
                    <div class="sm:flex sm:items-center">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            {{ __("Add new role") }}
                        </h3>
                        <p class="mt-1 sm:mt-0 sm:ml-3 text-sm leading-5 text-gray-500">{{ __("Add a new role and assign permissions for administrators.") }}</p>
                    </div>
                </div>
                <div class="p-4 sm:px-6 border-t border-gray-100">
                    <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                        <div class="sm:col-span-1">
                            <label for="name" class="block text-sm font-medium leading-5 text-gray-700">
                                {{ __("Name") }} <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-1 relative">
                                <input wire:model="name" id="name" class="form-input block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out sm:text-sm sm:leading-5" placeholder="manager" />
                            </div>
                            @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="sm:col-span-1">
                            <label for="display_name" class="block text-sm font-medium leading-5 text-gray-700">
                                {{ __("Display name") }}
                            </label>
                            <div class="mt-1 relative">
                                <input wire:model="role_display_name" id="display_name" class="form-input block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out sm:text-sm sm:leading-5" placeholder="Manager" />
                            </div>
                        </div>
                        <div class="sm:col-span-2">
                            <label for="role_description" class="block text-sm leading-5 font-medium text-gray-700">
                                {{ __("Description") }}
                            </label>
                            <div class="rounded-md shadow-sm">
                                <textarea wire:model="role_description" id="role_description" rows="3" class="form-textarea mt-1 block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                    <button
                        wire:click="addNewRole"
                        type="button"
                        class="inline-flex w-full items-center justify-center px-4 py-2 border border-transparent text-base leading-6 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-700 transition ease-in-out duration-150 sm:text-sm sm:leading-5"
                    >
                        <svg wire:loading wire:target="addNewRole" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                        </svg>
                        {{ __('Save') }}
                    </button>
                </span>
                <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                    <button @click="show = false;" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-brand-300 focus:shadow-outline transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                        {{ __('Cancel') }}
                    </button>
                </span>
            </div>
        </div>
    </div>

</div>
