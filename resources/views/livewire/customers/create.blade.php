@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/intl-tel-input@17.0.3/build/css/intlTelInput.min.css">
@endpush

<div>
    <x:shopper-breadcrumb back="shopper.customers.index">
        <svg class="flex-shrink-0 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
        </svg>
        <a href="{{ route('shopper.customers.index') }}" class="text-gray-500 hover:text-gray-700 focus:outline-none focus:underline transition duration-150 ease-in-out">{{ __('Customers') }}</a>
    </x:shopper-breadcrumb>

    <div class="mt-3 pb-5 border-b border-gray-200 space-y-3 md:flex md:items-center md:justify-between md:space-y-0">
        <h3 class="text-2xl font-bold leading-6 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
            {{ __("Create customer") }}
        </h3>
    </div>

    <div class="mt-6">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-semibold leading-6 text-gray-900">{{ __("Profile overview") }}</h3>
                    <p class="mt-4 text-sm leading-5 text-gray-600">
                        {{ __("Use a permanent address where customer can receive mail.") }}
                    </p>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <div class="shadow bg-white rounded-md px-4 py-5 sm:p-6 ">
                    <div class="grid gap-4 grid-cols-6 sm:gap-5">
                        <div class="col-span-6 sm:col-span-3">
                            <x-shopper-input.group label="First name" for="first_name" :error="$errors->first('first_name')">
                                <input wire:model="first_name" id="first_name" type="text" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" autocomplete="off" />
                            </x-shopper-input.group>
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <x-shopper-input.group label="Last name" for="last_name" :error="$errors->first('last_name')">
                                <input wire:model="last_name" id="last_name" type="text" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" autocomplete="off" />
                            </x-shopper-input.group>
                        </div>

                        <div class="col-span-6">
                            <x-shopper-input.group label="Email Address" for="email" :error="$errors->first('email')">
                                <input wire:model="email" id="email" type="email" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" autocomplete="off" />
                            </x-shopper-input.group>
                        </div>

                        <div
                            x-data
                            wire:ignore
                            x-init="
                                    phoneNumber = document.querySelector('#phone_number');
                                    iti = intlTelInput(document.querySelector('#phone_number'), {
                                        nationalMode: true,
                                        geoIpLookup: function(success, failure) {
                                            $.get('https://ipinfo.io', function() {}, 'jsonp').always(function(resp) {
                                                var countryCode = (resp && resp.country) ? resp.country : '';
                                                success(countryCode);
                                            });
                                        },
                                        utilsScript: 'https://unpkg.com/intl-tel-input@17.0.3/build/js/utils.js'
                                    });
                                    var handleChange = () => {
                                        if (iti.isValidNumber()) {
                                            phoneNumber.value = iti.getNumber();
                                        }
                                      };
                                    phoneNumber.addEventListener('change', handleChange);
                                    phoneNumber.addEventListener('keyup', handleChange);
                               "
                            class="col-span-6"
                        >
                            <div class="flex items-center justify-between">
                                <label for="phone_number" class="block text-sm font-medium leading-5 text-gray-700">{{ __("Phone number") }}</label>
                                <span class="text-gray-500 text-sm leading-5">{{ __("Optional") }}</span>
                            </div>
                            <div class="mt-1 relative">
                                <input wire:model="phone_number" id="phone_number" type="tel" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5 pr-10" autocomplete="off" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="hidden sm:block">
        <div class="py-5">
            <div class="border-t border-gray-200"></div>
        </div>
    </div>

    <div class="mt-10 sm:mt-0">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-semibold leading-6 text-gray-900">{{ __("Security") }}</h3>
                    <p class="mt-4 text-sm leading-5 text-gray-600">
                        {{ __("Enter a random password that this user will use to login to his account.") }}
                    </p>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <div class="shadow rounded-md overflow-hidden">
                    <div class="px-4 py-5 bg-white sm:p-6 space-y-4">
                        <div class="grid gap-4 sm:grid-cols-6 sm:gap-6">
                            <div x-data="{ show: false }" class="sm:col-span-6">
                                <div class="flex items-center justify-between">
                                    <label for="password" class="block text-sm font-medium leading-5 text-gray-700">
                                        {{ __("Password") }}
                                    </label>
                                    <div class="flex items-center divide-x divide-gray-200">
                                        <button wire:click="generate" type="button" class="pr-2 text-blue-600 text-sm font-medium leading-5 hover:text-blue-500 transition ease-in-out duration-150">
                                            {{ __("Generate") }}
                                        </button>
                                        <button
                                            @click="show = !show"
                                            x-text="show ? '{{ __("Hide") }}' : '{{ __("Show") }}'"
                                            type="button"
                                            class="pl-2 text-sm text-leading-5 text-blue-600 hover:text-blue-500 focus:outline-none focus:text-blue-700 hover:underline">
                                        </button>
                                    </div>
                                </div>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input wire:model="password" id="password" :type="show ? 'text' : 'password'" autocomplete="off" class="form-input block w-full sm:text-sm sm:leading-5 transition duration-150 ease-in-out @error('password') pr-10 border-red-300 text-red-900 placeholder-red-300 focus:border-red-300 focus:shadow-outline-red @enderror" />
                                    @error('password')
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    @enderror
                                </div>
                                @error('password')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="sm:col-span-6">
                                <x-shopper-input.group label="Confirm Password" for="password_confirmation" :error="$errors->first('password_confirmation')">
                                    <input wire:model="password_confirmation" id="password_confirmation" type="password" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" autocomplete="off" />
                                </x-shopper-input.group>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="hidden sm:block">
        <div class="py-5">
            <div class="border-t border-gray-200"></div>
        </div>
    </div>

    <div class="mt-10 sm:mt-0">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-semibold leading-6 text-gray-900">{{ __("Address") }}</h3>
                    <p class="mt-4 text-sm leading-5 text-gray-600">
                        {{ __("The primary address of this customer. This address will defined as default shipping address.") }}
                    </p>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <div class="shadow rounded-md overflow-hidden">
                    <div class="px-4 py-5 bg-white sm:p-6 space-y-4">
                        <div class="grid gap-4 sm:grid-cols-6 sm:gap-6">
                            <div class="col-span-6 sm:col-span-3">
                                <x-shopper-input.group label="First name" for="address_first_name" :error="$errors->first('address_first_name')">
                                    <input wire:model="address_first_name" id="address_first_name" type="text" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" autocomplete="off" />
                                </x-shopper-input.group>
                            </div>
                            <div class="col-span-6 sm:col-span-3">
                                <x-shopper-input.group label="Last name" for="address_last_name" :error="$errors->first('address_last_name')">
                                    <input wire:model="address_last_name" id="address_last_name" type="text" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" autocomplete="off" />
                                </x-shopper-input.group>
                            </div>
                            <div class="col-span-6">
                                <x-shopper-input.group label="Company name" for="company_name">
                                    <input wire:model="company_name" id="company_name" type="text" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" autocomplete="off" />
                                </x-shopper-input.group>
                            </div>
                            <div class="col-span-6">
                                <x-shopper-input.group label="Street Address" for="street_address" :error="$errors->first('street_address')">
                                    <input wire:model="street_address" id="street_address" type="text" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" autocomplete="off" />
                                </x-shopper-input.group>
                            </div>
                            <div class="col-span-6">
                                <x-shopper-input.group label="Apartment, suite, etc." for="street_address_plus">
                                    <input wire:model="street_address_plus" id="street_address_plus" type="text" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" autocomplete="off" />
                                </x-shopper-input.group>
                            </div>
                            <div class="col-span-6">
                                <x-shopper-input.group label="City" for="city" :error="$errors->first('city')">
                                    <input wire:model="city" id="city" type="text" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" autocomplete="off" />
                                </x-shopper-input.group>
                            </div>
                            <div class="col-span-6 sm:col-span-3">
                                <label for="country_id" class="block text-sm font-medium leading-5 text-gray-700">
                                    {{ __("Country/Region") }}
                                </label>
                                <div class="mt-1 rounded-md shadow-sm">
                                    <select wire:model="country_id" id="country_id" class="form-select block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                                        <option>{{ __("Country/Region") }}</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('country_id')
                                    <p class="text-red-500 leading-5 text-sm">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="col-span-6 sm:col-span-3">
                                <x-shopper-input.group label="Postal / Zip code" for="zipcode" :error="$errors->first('zipcode')">
                                    <input wire:model="zipcode" id="zipcode" type="text" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" autocomplete="off" />
                                </x-shopper-input.group>
                            </div>
                            <div
                                x-data
                                wire:ignore
                                x-init="
                                    phoneNumber = document.querySelector('#address_phone_number');
                                    iti = intlTelInput(document.querySelector('#address_phone_number'), {
                                        nationalMode: true,
                                        geoIpLookup: function(success, failure) {
                                            $.get('https://ipinfo.io', function() {}, 'jsonp').always(function(resp) {
                                                var countryCode = (resp && resp.country) ? resp.country : '';
                                                success(countryCode);
                                            });
                                        },
                                        utilsScript: 'https://unpkg.com/intl-tel-input@17.0.3/build/js/utils.js'
                                    });
                                    var handleChange = () => {
                                        if (iti.isValidNumber()) {
                                            phoneNumber.value = iti.getNumber();
                                        }
                                      };
                                    phoneNumber.addEventListener('change', handleChange);
                                    phoneNumber.addEventListener('keyup', handleChange);
                               "
                                class="col-span-6"
                            >
                                <div class="flex items-center justify-between">
                                    <label for="address_phone_number" class="block text-sm font-medium leading-5 text-gray-700">{{ __("Phone number") }}</label>
                                </div>
                                <div class="mt-1 relative">
                                    <input wire:model="address_phone_number" id="address_phone_number" type="tel" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5 pr-10" autocomplete="off" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="hidden sm:block">
        <div class="py-5">
            <div class="border-t border-gray-200"></div>
        </div>
    </div>

    <div class="mt-10 sm:mt-0">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-semibold leading-6 text-gray-900">{{ __("Notifications") }}</h3>
                    <p class="mt-4 text-sm leading-5 text-gray-600">
                        {{ __("Inform your customers about their account.") }}
                    </p>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <div class="shadow rounded-md overflow-hidden">
                    <div class="px-4 py-5 bg-white sm:p-6 space-y-4">
                        <div class="space-y-6">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input wire:model="opt_in" id="opt_in" type="checkbox" class="form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out">
                                </div>
                                <div class="ml-3 text-sm leading-5">
                                    <label for="opt_in" class="cursor-pointer font-medium text-gray-700">{{ __("Customer agreed to receive marketing emails.") }}</label>
                                    <p class="text-sm mt-1 leading-5 text-gray-500">{{ __("You should ask your customers for permission before you subscribe them to your marketing emails if you got one.") }}</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input wire:model="send_mail" id="send_mail" type="checkbox" class="form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out">
                                </div>
                                <div class="ml-3 text-sm leading-5">
                                    <label for="send_mail" class="cursor-pointer font-medium text-gray-700">{{ __("Send customer credentials.") }}</label>
                                    <p class="text-sm mt-1 leading-5 text-gray-500">{{ __("An email will be sent to this customer with these connection credentials.") }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6 border-t border-gray-200 pt-5 pb-10">
        <div class="flex justify-end">
            <x-shopper-button wire:click="store" type="button" wire:loading.attr="disabled">
                <svg wire:loading wire:target="store" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                </svg>
                {{ __("Save") }}
            </x-shopper-button>
        </div>
    </div>

</div>

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://unpkg.com/intl-tel-input@17.0.3/build/js/intlTelInput.min.js"></script>
@endpush
