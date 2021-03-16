@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/intl-tel-input@17.0.3/build/css/intlTelInput.min.css">
@endpush

<div>
    <x:shopper-breadcrumb back="shopper.settings.inventories.index">
        <svg class="flex-shrink-0 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
        </svg>
        <a href="{{ route('shopper.settings.inventories.index') }}" class="text-gray-500 hover:text-gray-700 focus:outline-none focus:underline transition duration-150 ease-in-out">{{ __('Locations') }}</a>
    </x:shopper-breadcrumb>

    <div class="mt-3 pb-5 border-b border-gray-200 space-y-3 md:flex md:items-center md:justify-between md:space-y-0">
        <h3 class="text-2xl font-bold leading-6 text-gray-900 sm:text-3xl sm:leading-9 sm:truncate">
            {{ __("Add location") }}
        </h3>
        <div class="flex">
            <x-shopper-button wire:click="store" wire.loading.attr="disabled" type="button">
                <svg wire:loading wire:target="store" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                </svg>
                {{ __("Save") }}
            </x-shopper-button>
        </div>
    </div>

    <div class="mt-6">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-medium leading-6 text-gray-700">{{ __("Details") }}</h3>
                    <p class="mt-1 text-sm leading-5 text-gray-500">
                        {{ __("Give this location a short name to make it easy to identify. You’ll see this name in areas like products.") }}
                    </p>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <div class="bg-white shadow rounded-md overflow-hidden p-4 sm:p-5">
                    <div class="space-y-4">
                        <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                            <div class="sm:col-span-1">
                                <x-shopper-input.group label="Location Name" for="name" :error="$errors->first('name')">
                                    <x-shopper-input.text wire:model="name" id="name" type="text" autocomplete="off" placeholder="White House" />
                                </x-shopper-input.group>
                            </div>
                            <div class="sm:col-span-1">
                                <x-shopper-input.group label="Email" for="email" :error="$errors->first('email')">
                                    <x-shopper-input.text wire:model="email" id="email" type="email" autocomplete="off" />
                                </x-shopper-input.group>
                            </div>
                            <div class="sm:col-span-2">
                                <div class="flex items-center justify-between">
                                    <x-shopper-label :value="__('Description')" for="description" />
                                    <span class="ml-4 text-sm text-gray-500 leading-5">{{ __("Optional") }}</span>
                                </div>
                                <div class="mt-1 relative shadow-sm rounded-md">
                                    <x-shopper-input.textarea wire:model="description" id="description" />
                                </div>
                            </div>
                        </div>
                        <div class="relative flex items-start">
                            <div class="flex items-center h-5">
                                <input wire:model="isDefault" id="isDefault" type="checkbox" class="form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out" />
                            </div>
                            <div class="ml-3 text-sm leading-5">
                                <label for="isDefault" class="font-medium text-gray-700 cursor-pointer">{{ __("Set as default inventory") }}</label>
                                <p class="text-gray-500">{{ __("Inventory at this location is available for sale online and will use as default.") }}</p>
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
                    <h3 class="text-lg font-semibold leading-6 text-gray-900">{{ __("Inventory address") }}</h3>
                    <p class="mt-4 text-sm leading-5 text-gray-500">
                        {{ __("Your inventory's complete information. Please put valide informations this can be accessible for your customers.") }}
                    </p>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <div class="shadow bg-white rounded-md overflow-hidden">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="grid gap-4 sm:grid-cols-6 sm:gap-6">
                            <div class="sm:col-span-6">
                                <x-shopper-input.group label="Street address" for="street_address" :error="$errors->first('street_address')">
                                    <x-shopper-input.text wire:model="street_address" id="street_address" type="text" autocomplete="off" placeholder="Akwa Avenue 34..." />
                                </x-shopper-input.group>
                            </div>

                            <div class="sm:col-span-6">
                                <div class="flex items-center justify-between">
                                    <x-shopper-label :value="__('Apartment, suite, etc.')" for="street_address_plus" />
                                    <span class="ml-4 text-sm text-gray-500 leading-5">{{ __("Optional") }}</span>
                                </div>
                                <div class="mt-1 relative shadow-sm rounded-md">
                                    <x-shopper-input.text wire:model="street_address_plus" id="street_address_plus" type="text" autocomplete="off" />
                                </div>
                            </div>

                            <div class="sm:col-span-6">
                                <x-shopper-input.group for="country_id" label="Country" :error="$errors->first('country_id')">
                                    <x-shopper-input.select wire:model="country_id" id="country_id">
                                        <option value="0">{{ __("Choose a Country") }}</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                                        @endforeach
                                    </x-shopper-input.select>
                                </x-shopper-input.group>
                            </div>

                            <div class="sm:col-span-3">
                                <x-shopper-input.group label="City" for="city" :error="$errors->first('city')">
                                    <x-shopper-input.text wire:model="city" id="city" type="text" autocomplete="off" />
                                </x-shopper-input.group>
                            </div>

                            <div class="sm:col-span-3">
                                <x-shopper-input.group label="Postal / Zip code" for="zipcode" :error="$errors->first('zipcode')">
                                    <x-shopper-input.text wire:model="zipcode" id="zipcode" type="text" autocomplete="off" />
                                </x-shopper-input.group>
                            </div>

                            <div
                                x-data
                                wire:ignore
                                x-init="
                                    phoneNumber = document.querySelector('#phone_number');
                                    iti = intlTelInput(document.querySelector('#phone_number'), {
                                        nationalMode: true,
                                        initialCountry: 'auto',
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
                                class="sm:col-span-6"
                            >
                                <x-shopper-input.group label="Phone number" for="phone_number" :error="$errors->first('phone_number')">
                                    <x-shopper-input.text wire:model="phone_number" id="phone_number" type="tel" class="pr-10" autocomplete="off" />
                                    @error('phone_number')
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    @enderror
                                </x-shopper-input.group>
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
