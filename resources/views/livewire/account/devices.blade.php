<div class="mt-10 sm:mt-0">
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-semibold leading-6 text-gray-900">{{ __("Devices") }}</h3>
                <p class="mt-4 text-sm leading-5 text-gray-600">
                    {{ __("You're currently logged in on these devices. If you don't recognize a device, log out to keep your account secure.") }}
                </p>
            </div>
        </div>
        <div class="mt-5 md:mt-0 md:col-span-2">
            <div class="shadow rounded-md overflow-hidden">
                <div class="px-4 py-5 bg-white sm:p-6">
                    @if (count($this->sessions) > 0)
                        <p class="text-gray-500 text-sm">
                            {{ __("If necessary, you may logout of all of your other browser sessions across all of your devices.") }}
                        </p>
                        <div class="mt-2 divide-y divide-gray-200">
                            @foreach($this->sessions as $session)
                                <div class="py-4 flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0">
                                            @if ($session->agent->isDesktop())
                                                <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                </svg>
                                            @else
                                                <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                </svg>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="flex items-center">
                                                <h4 class="text-sm leading-5 text-gray-600">
                                                    <span class="text-green-500">{{ $session->agent->browser() }} {{ __("on") }} {{ $session->agent->platform() }}</span> - {{ $session->ip_address }}
                                                </h4>
                                                @if ($session->is_current_device)
                                                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium leading-4 bg-teal-100 text-teal-800">
                                                        {{ __("This device") }}
                                                    </span>
                                                @else
                                                    <span class="ml-2 text-xs text-gray-500">{{ __('Last active') }} {{ $session->last_active }}</span>
                                                @endif
                                            </div>
                                            <p class="mt-0.5 text-sm leading-4 text-gray-500">
                                                @if($session->location)
                                                    {{ $session->location->cityName }}, {{ $session->location->regionName }}, {{ $session->location->countryName }}
                                                @else
                                                    {{ __("Unable to recover this location.") }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    @if (!$session->is_current_device)
                                        <div class="ml-4">
                                            <span class="inline-flex rounded-md shadow-sm">
                                                <button wire:click="confirmLogout" wire:loading.attr="disabled" type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm leading-5 font-medium rounded-md text-gray-700 bg-white hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:text-gray-800 active:bg-gray-50 transition ease-in-out duration-150">
                                                    {{ __("Log out") }}
                                                </button>
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm leading-5 text-yellow-700">
                                        {{ __("Database session driver are needed to enable this feature.") }}
                                        <a href="https://laravel.com/docs/session" target="_blank" class="font-medium underline text-yellow-700 hover:text-yellow-600 transition ease-in-out duration-150">
                                            {{ __("Learn more") }} &rarr;
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div
         x-data="{ show: @if($confirmingLogout) true @else false @endif }"
         x-show="show"
         x-on:keydown.escape.window="show = false"
         x-on:confirming-logout-other-browser-sessions.window="show = true"
         class="fixed top-0 inset-x-0 px-4 pt-6 z-50 sm:px-0 sm:flex sm:items-top sm:justify-center"
         style="display: none;"
    >
        <div
             x-show="show"
             x-on:click="show = false"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 transform transition-all"
        >
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <div
             x-show="show"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
        >
            <div class="bg-white">
                <div class="sm:flex sm:items-start px-4 sm:px-6 pt-4">
                    <div class="text-left">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            {{ __('Logout Other Browser Sessions') }}
                        </h3>
                        <p class="mt-1 text-sm text-gray-500 leading-5">
                            {{ __('Please enter your password to confirm you would like to logout of your other browser sessions across all of your devices.') }}
                        </p>
                    </div>
                </div>
                <div class="p-4 sm:px-6">
                    <div>
                        <div class="relative">
                            <input wire:model="password" aria-label="{{ __("Password") }}" type="password" class="form-input block w-full py-2 px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out sm:text-sm sm:leading-5" placeholder="{{ __("Enter your password") }}" />
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <span class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                    <button wire:click="logoutOtherBrowserSessions" wire:loading.attr="disabled" type="button" class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-gray-800 text-base leading-6 font-medium text-white shadow-sm hover:bg-gray-700 focus:outline-none focus:border-gray-600 focus:shadow-outline-gray transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                        <svg wire:loading wire:target="logoutOtherBrowserSessions" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                        </svg>
                        {{ __("Logout Other Browser Sessions") }}
                    </button>
                </span>
                <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">
                    <button wire:click="$toggle('confirmingLogout')" wire:loading.attr="disabled" type="button" class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                        {{ __("Nevermind") }}
                    </button>
                </span>
            </div>
        </div>
    </div>
</div>
