@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/intl-tel-input@17.0.3/build/css/intlTelInput.min.css">
@endpush

<div>
    <x:shopper-breadcrumb back="shopper.customers.index">
        <x-heroicon-s-chevron-left class="flex-shrink-0 h-5 w-5 text-gray-400" />
        <x-shopper-breadcrumb-link :link="route('shopper.customers.index')" title="Customers" />
    </x:shopper-breadcrumb>

    <x-shopper-heading class="mt-3">
        <x-slot name="title">
            {{ __('Create customer') }}
        </x-slot>
    </x-shopper-heading>

    <div class="mt-6">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-bold leading-6 text-gray-900 dark:text-white">{{ __('Profile overview') }}</h3>
                    <p class="mt-4 text-sm leading-5 text-gray-500 dark:text-gray-400">
                        {{ __('Use a permanent address where customer can receive mail.') }}
                    </p>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <div class="px-4 py-5 sm:p-6 shadow bg-white rounded-md dark:bg-gray-800">
                    <div class="grid gap-4 grid-cols-6 sm:gap-5">
                        <x-shopper-input.group label="First name" for="first_name" class="col-span-6 sm:col-span-3" :error="$errors->first('first_name')">
                            <x-shopper-input.text wire:model.lazy="first_name" id="first_name" type="text" autocomplete="off" />
                        </x-shopper-input.group>

                        <x-shopper-input.group label="Last name" for="last_name" class="col-span-6 sm:col-span-3" :error="$errors->first('last_name')">
                            <x-shopper-input.text wire:model.lazy="last_name" id="last_name" type="text" autocomplete="off" />
                        </x-shopper-input.group>

                        <x-shopper-input.group label="Email Address" for="email" class="col-span-6" :error="$errors->first('email')">
                            <x-shopper-input.text wire:model.debounce.350ms="email" id="email" type="email" autocomplete="off" />
                        </x-shopper-input.group>

                        <div
                            x-data
                            wire:ignore
                            x-init="
                                    phoneNumber = document.querySelector('#phone_number');
                                    iti = intlTelInput(document.querySelector('#phone_number'), {
                                        nationalMode: true,
                                        geoIpLookup: function(success, failure) {
                                            $.get('https://ipinfo.io', function() {}, 'jsonp').always(function(resp) {
                                                var countryCode = (resp && resp.country) ? resp.country : 'CM';
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
                                <x-shopper-label for="phone_number" :value="__('Phone number')" />
                                <span class="text-gray-500 text-sm leading-5 dark:text-gray-400">{{ __('Optional') }}</span>
                            </div>
                            <div class="mt-1 relative">
                                <x-shopper-input.text wire:model="phone_number" id="phone_number" type="tel" class="pr-10" autocomplete="off" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="hidden sm:block">
        <div class="py-5">
            <div class="border-t border-gray-200 dark:border-gray-700"></div>
        </div>
    </div>

    <div class="mt-10 sm:mt-0">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-bold leading-6 text-gray-900 dark:text-white">{{ __('Security') }}</h3>
                    <p class="mt-4 text-sm leading-5 text-gray-500 dark:txt-gray-400">
                        {{ __('Enter a random password that this user will use to login to his account.') }}
                    </p>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <div class="bg-white shadow rounded-md overflow-hidden dark:bg-gray-800">
                    <div class="px-4 py-5 sm:p-6 space-y-4">
                        <div class="grid gap-4 sm:grid-cols-6 sm:gap-6">
                            <div x-data="{ show: false }" class="sm:col-span-6">
                                <div class="flex items-center justify-between">
                                    <x-shopper-label for="password" :value="__('Password')" />
                                    <div class="flex items-center divide-x divide-gray-200 dark:divide-gray-600">
                                        <button wire:click="generate" type="button" class="pr-2 text-blue-600 text-sm font-medium leading-5 hover:text-blue-500 dark:text-blue-400">
                                            {{ __('Generate') }}
                                        </button>
                                        <button
                                            @click="show = !show"
                                            x-text="show ? '{{ __("Hide") }}' : '{{ __("Show") }}'"
                                            type="button"
                                            class="pl-2 text-sm text-leading-5 text-blue-600 hover:text-blue-500 focus:outline-none focus:text-blue-700 hover:underline dark:text-blue-400">
                                        </button>
                                    </div>
                                </div>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input wire:model="password" id="password" :type="show ? 'text' : 'password'" autocomplete="off" class="block w-full dark:bg-gray-700 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 rounded-md shadow-sm border-gray-300 dark:border-gray-700 focus:border-blue-300 focus:ring focus:ring-blue-300 dark:focus:ring-offset-gray-900 focus:ring-opacity-50 sm:text-sm @error('password') pr-10 border-red-300 text-red-900 placeholder-red-300 focus:border-red-300 @enderror" />
                                    @error('password')
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <x-heroicon-s-exclamation-circle class="h-5 w-5 text-red-500" />
                                        </div>
                                    @enderror
                                </div>
                                @error('password')
                                    <p class="mt-2 text-sm text-red-500 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <x-shopper-input.group class="sm:col-span-6" label="Confirm Password" for="password_confirmation" :error="$errors->first('password_confirmation')">
                                <x-shopper-input.text wire:model.lazy="password_confirmation" id="password_confirmation" type="password" autocomplete="off" />
                            </x-shopper-input.group>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="hidden sm:block">
        <div class="py-5">
            <div class="border-t border-gray-200 dark:border-gray-700"></div>
        </div>
    </div>

    <div class="mt-10 sm:mt-0">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-bold leading-6 text-gray-900 dark:text-white">{{ __('Address') }}</h3>
                    <p class="mt-4 text-sm leading-5 text-gray-500 dark:text-gray-400">
                        {{ __('The primary address of this customer. This address will defined as default shipping address.') }}
                    </p>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <div class="bg-white shadow rounded-md dark:bg-gray-800">
                    <div class="px-4 py-5 sm:p-6 space-y-4">
                        <div class="grid gap-4 sm:grid-cols-6 sm:gap-6">
                            <x-shopper-input.group class="col-span-6 sm:col-span-3" label="First name" for="address_first_name" :error="$errors->first('address_first_name')">
                                <x-shopper-input.text wire:model.lazy="address_first_name" id="address_first_name" type="text" autocomplete="off" />
                            </x-shopper-input.group>

                            <x-shopper-input.group class="col-span-6 sm:col-span-3" label="Last name" for="address_last_name" :error="$errors->first('address_last_name')">
                                <x-shopper-input.text wire:model="address_last_name" id="address_last_name" type="text" autocomplete="off" />
                            </x-shopper-input.group>

                            <x-shopper-input.group class="col-span-6" label="Company name" for="company_name" optional>
                                <x-shopper-input.text wire:model.lazy="company_name" id="company_name" type="text" autocomplete="off" />
                            </x-shopper-input.group>

                            <x-shopper-input.group class="col-span-6" label="Street Address" for="street_address" :error="$errors->first('street_address')">
                                <x-shopper-input.text wire:model.lazy="street_address" id="street_address" type="text" autocomplete="off" />
                            </x-shopper-input.group>

                            <x-shopper-input.group class="col-span-6" label="Apartment, suite, etc." for="street_address_plus" optional>
                                <x-shopper-input.text wire:model.lazy="street_address_plus" id="street_address_plus" type="text" autocomplete="off" />
                            </x-shopper-input.group>

                            <x-shopper-input.group class="col-span-6" label="City" for="city" :error="$errors->first('city')">
                                <x-shopper-input.text wire:model.lazy="city" id="city" type="text" autocomplete="off" />
                            </x-shopper-input.group>

                            <x-shopper-input.group class="col-span-6 sm:col-span-3" for="country_id" label="Country/Region" :error="$errors->first('country_id')">
                                <x-shopper-input.select wire:model.debounce.250ms="country_id" id="country_id">
                                    <option>{{ __('Country/Region') }}</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                    @endforeach
                                </x-shopper-input.select>
                            </x-shopper-input.group>

                            <x-shopper-input.group label="Postal / Zip code" for="zipcode" class="col-span-6 sm:col-span-3" :error="$errors->first('zipcode')">
                                <x-shopper-input.text wire:model="zipcode" id="zipcode" type="text" autocomplete="off" />
                            </x-shopper-input.group>

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
                                    <x-shopper-label for="address_phone_number" :value="__('Phone number')" />
                                </div>
                                <div class="mt-1 relative">
                                    <x-shopper-input.text wire:model="address_phone_number" id="address_phone_number" type="tel" class="pr-10" autocomplete="off" />
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
            <div class="border-t border-gray-200 dark:border-gray-700"></div>
        </div>
    </div>

    <div class="mt-10 sm:mt-0">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-bold leading-6 text-gray-900 dark:text-white">{{ __('Notifications') }}</h3>
                    <p class="mt-4 text-sm leading-5 text-gray-500 dark:text-gray-400">
                        {{ __('Inform your customers about their account.') }}
                    </p>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <div class="bg-white shadow rounded-md overflow-hidden dark:bg-gray-800">
                    <div class="px-4 py-5 sm:p-6 space-y-4">
                        <div class="space-y-6">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <x-shopper-input.checkbox wire:model.lazy="opt_in" id="opt_in" />
                                </div>
                                <div class="ml-3 text-sm leading-5">
                                    <x-shopper-label for="opt_in" :value="__('Customer agreed to receive marketing emails.')" />
                                    <p class="text-sm mt-1 leading-5 text-gray-500 dark:text-gray-400">{{ __('You should ask your customers for permission before you subscribe them to your marketing emails if you got one.') }}</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <x-shopper-input.checkbox wire:model.lazy="send_mail" id="send_mail" />
                                </div>
                                <div class="ml-3 text-sm leading-5">
                                    <x-shopper-label for="send_mail" :value="__('Send customer credentials.')" />
                                    <p class="text-sm mt-1 leading-5 text-gray-500 dark:text-gray-400">{{ __('An email will be sent to this customer with these connection credentials.') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6 pt-5 border-t border-gray-200 dark:border-gray-700">
        <div class="flex justify-end">
            <x-shopper-button wire:click="store" type="button" wire:loading.attr="disabled">
                <x-shopper-loader wire:loading wire:target="store" class="text-white" />
                {{ __('Save') }}
            </x-shopper-button>
        </div>
    </div>

</div>

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://unpkg.com/intl-tel-input@17.0.3/build/js/intlTelInput.min.js"></script>
@endpush
