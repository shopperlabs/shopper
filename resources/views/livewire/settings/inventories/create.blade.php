<div>
    <x:shopper-breadcrumb back="shopper.settings.inventories.index">
        <x-heroicon-s-chevron-left class="shrink-0 h-5 w-5 text-secondary-400" />
        <x-shopper-breadcrumb-link :link="route('shopper.settings.inventories.index')" title="Locations" />
    </x:shopper-breadcrumb>

    <x-shopper-heading class="mt-3">
        <x-slot name="title">
            {{ __('Add location') }}
        </x-slot>

        <x-slot name="action">
            <div class="flex">
                <x-shopper-button wire:click="store" wire.loading.attr="disabled" type="button">
                    <x-shopper-loader wire:loading wire:target="store" class="text-white" />
                    {{ __('Save') }}
                </x-shopper-button>
            </div>
        </x-slot>
    </x-shopper-heading>

    <div class="mt-6">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-bold leading-6 text-secondary-900 dark:text-white">{{ __('Details') }}</h3>
                    <p class="mt-2 text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                        {{ __("Give this location a short name to make it easy to identify. You’ll see this name in areas like products.") }}
                    </p>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <div class="p-4 sm:p-5 bg-white shadow rounded-md overflow-hidden dark:bg-secondary-800">
                    <div class="space-y-4">
                        <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                            <div class="sm:col-span-1">
                                <x-shopper-input.group label="Location Name" for="name" :error="$errors->first('name')">
                                    <x-shopper-input.text wire:model.defer="name" id="name" type="text" autocomplete="off" placeholder="White House" />
                                </x-shopper-input.group>
                            </div>
                            <div class="sm:col-span-1">
                                <x-shopper-input.group label="Email" for="email" :error="$errors->first('email')">
                                    <x-shopper-input.text wire:model.defer="email" id="email" type="email" autocomplete="off" />
                                </x-shopper-input.group>
                            </div>
                            <div class="sm:col-span-2">
                                <div class="flex items-center justify-between">
                                    <x-shopper-label :value="__('Description')" for="description" />
                                    <span class="ml-4 text-sm leading-5 text-secondary-500 dark:text-secondary-400">{{ __('Optional') }}</span>
                                </div>
                                <div class="mt-1 relative shadow-sm rounded-md">
                                    <x-shopper-input.textarea wire:model.defer="description" id="description" />
                                </div>
                            </div>
                        </div>
                        <div class="relative flex items-start">
                            <div class="flex items-center h-5">
                                <x-shopper-input.checkbox wire:model.defer="isDefault" id="isDefault" />
                            </div>
                            <div class="ml-3 text-sm leading-5">
                                <label for="isDefault" class="font-medium text-secondary-700 cursor-pointer dark:text-secondary-200">{{ __('Set as default inventory') }}</label>
                                <p class="text-secondary-500 dark:text-secondary-400">{{ __('Inventory at this location is available for sale online and will use as default.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="hidden sm:block">
        <div class="py-5">
            <div class="border-t border-secondary-200 dark:border-secondary-700"></div>
        </div>
    </div>

    <div class="mt-10 sm:mt-0">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-bold leading-6 text-secondary-900 dark:text-white">{{ __('Inventory address') }}</h3>
                    <p class="mt-2 text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                        {{ __("Your inventory's complete information. Please put valide informations this can be accessible for your customers.") }}
                    </p>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <div class="bg-white shadow rounded-md dark:bg-secondary-800">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="grid gap-4 sm:grid-cols-6 sm:gap-6">
                            <div class="sm:col-span-6">
                                <x-shopper-input.group label="Street address" for="street_address" :error="$errors->first('street_address')">
                                    <x-shopper-input.text wire:model.defer="street_address" id="street_address" type="text" autocomplete="off" placeholder="Akwa Avenue 34..." />
                                </x-shopper-input.group>
                            </div>

                            <div class="sm:col-span-6">
                                <div class="flex items-center justify-between">
                                    <x-shopper-label :value="__('Apartment, suite, etc.')" for="street_address_plus" />
                                    <span class="ml-4 text-sm leading-5 text-secondary-500 dark:text-secondary-400">{{ __('Optional') }}</span>
                                </div>
                                <div class="mt-1 relative shadow-sm rounded-md">
                                    <x-shopper-input.text wire:model.defer="street_address_plus" id="street_address_plus" type="text" autocomplete="off" />
                                </div>
                            </div>

                            <div class="sm:col-span-6">
                                <x-shopper-input.group for="country_id" label="Country" :error="$errors->first('country_id')">
                                    <x-select
                                        :placeholder="__('Choose a Country')"
                                        wire:model.defer="country_id"
                                    >
                                        @foreach($countries as $country)
                                            <x-select.option :label="$country->name" :value="$country->id" />
                                        @endforeach
                                    </x-select>
                                </x-shopper-input.group>
                            </div>

                            <div class="sm:col-span-3">
                                <x-shopper-input.group label="City" for="city" :error="$errors->first('city')">
                                    <x-shopper-input.text wire:model.defer="city" id="city" type="text" autocomplete="off" />
                                </x-shopper-input.group>
                            </div>

                            <div class="sm:col-span-3">
                                <x-shopper-input.group label="Postal / Zip code" for="zipcode" :error="$errors->first('zipcode')">
                                    <x-shopper-input.text wire:model.defer="zipcode" id="zipcode" type="text" autocomplete="off" />
                                </x-shopper-input.group>
                            </div>

                            <div
                                wire:ignore
                                x-data="internationalNumber('#phone_number')"
                                class="sm:col-span-6"
                            >
                                <x-shopper-input.group label="Phone number" for="phone_number" :error="$errors->first('phone_number')">
                                    <x-shopper-input.text wire:model.defer="phone_number" id="phone_number" type="tel" class="pr-10" autocomplete="off" />
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

    <div class="mt-6 pt-5 border-t border-secondary-200 dark:border-secondary-700">
        <div class="flex justify-end">
            <x-shopper-button wire:click="store" type="button" wire:loading.attr="disabled">
                <x-shopper-loader wire:loading wire:target="store" class="text-white" />
                {{ __('Save') }}
            </x-shopper-button>
        </div>
    </div>
</div>
