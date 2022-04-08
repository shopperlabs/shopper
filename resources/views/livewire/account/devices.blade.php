<div class="mt-10 sm:mt-0 pb-10">
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-bold leading-6 text-secondary-900 dark:text-white">{{ __('Devices') }}</h3>
                <p class="mt-4 text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                    {{ __("You're currently logged in on these devices. If you don't recognize a device, log out to keep your account secure.") }}
                </p>
            </div>
        </div>
        <div class="mt-5 md:mt-0 md:col-span-2">
            <div class="shadow rounded-md bg-white overflow-hidden dark:bg-secondary-800">
                <div class="px-4 py-5 sm:p-6">
                    @if (count($this->sessions) > 0)
                        <p class="text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                            {{ __('If necessary, you may logout of all of your other browser sessions across all of your devices.') }}
                        </p>
                        <div class="mt-2 divide-y divide-secondary-200 dark:divide-secondary-700">
                            @foreach($this->sessions as $session)
                                <div class="py-4 flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="shrink-0">
                                            @if ($session->agent->isDesktop())
                                                <x-heroicon-o-desktop-computer class="w-8 h-8 text-secondary-500" />
                                            @else
                                                <x-heroicon-o-device-mobile class="w-8 h-8 text-secondary-500" />
                                            @endif
                                        </div>
                                        <div>
                                            <div class="flex items-center">
                                                <h4 class="text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                                                    <span class="text-green-500">{{ $session->agent->browser() }} {{ __("on") }} {{ $session->agent->platform() }}</span> - {{ $session->ip_address }}
                                                </h4>
                                                @if ($session->is_current_device)
                                                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium leading-4 bg-green-100 text-green-800">
                                                        {{ __('This device') }}
                                                    </span>
                                                @else
                                                    <span class="ml-2 text-xs text-secondary-400 dark:text-secondary-500">{{ __('Last active') }} {{ $session->last_active }}</span>
                                                @endif
                                            </div>
                                            <p class="mt-0.5 text-sm leading-4 text-secondary-500 dark:text-secondary-400">
                                                @if($session->location)
                                                    {{ $session->location->cityName }}, {{ $session->location->regionName }}, {{ $session->location->countryName }}
                                                @else
                                                    {{ __('Unable to recover this location.') }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    @if (!$session->is_current_device)
                                        <div class="ml-4">
                                            <span class="inline-flex rounded-md shadow-sm">
                                                <x-shopper::buttons.primary wire:click="$emit('openModal', 'shopper-modals.logout-others-browser')" wire:loading.attr="disabled" type="button">
                                                    {{ __('Log out') }}
                                                </x-shopper::buttons.primary>
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                            <div class="flex">
                                <div class="shrink-0">
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

</div>
