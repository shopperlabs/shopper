<div>
    <x:shopper-breadcrumb back="shopper.settings.index">
        <svg class="flex-shrink-0 h-5 w-5 text-secondary-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
        </svg>
        <a href="{{ route('shopper.settings.index') }}" class="text-secondary-500 hover:text-secondary-600 dark:text-secondary-400 dark:hover:text-secondary-500">{{ __('Settings') }}</a>
    </x:shopper-breadcrumb>

    <div class="mt-2 space-y-4 sm:flex sm:items-center sm:justify-between sm:space-y-0 pb-5 border-b border-secondary-200 dark:border-secondary-700">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-6 text-secondary-900 dark:text-white sm:text-3xl sm:leading-9 sm:truncate">{{ __('Store Setting') }}</h2>
        </div>
    </div>

    <x-shopper-validation-errors />

    <div class="mt-8 md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-semibold leading-6 text-secondary-900 dark:text-white">{{ __('Store details') }}</h3>
                <p class="mt-4 text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                    {{ __('Your customers will use this information to contact you.') }}
                </p>
            </div>
        </div>
        <div class="mt-5 md:mt-0 md:col-span-2">
            <div class="shadow bg-white dark:bg-secondary-800 rounded-md">
                <div class="px-4 py-5 sm:p-6">
                    <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6">
                            <x-shopper-input.group label="Store name" for="shop_name" isRequired :error="$errors->first('shop_name')">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-secondary-400" fill="currentColor" viewBox="0 0 24 24">
                                        <path fill-rule="evenodd" d="M22 5H2a1 1 0 0 0-1 1v4a3 3 0 0 0 2 2.82V22a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1v-9.18A3 3 0 0 0 23 10V6a1 1 0 0 0-1-1zm-7 2h2v3a1 1 0 1 1-2 0zm-4 0h2v3a1 1 0 1 1-2 0zM7 7h2v3a1 1 0 1 1-2 0zm-3 4a1 1 0 0 1-1-1V7h2v3a1 1 0 0 1-1 1zm10 10h-4v-2a2 2 0 1 1 4 0zm5 0h-3v-2a4 4 0 1 0-8 0v2H5v-8.18a3.17 3.17 0 0 0 1-.6 3 3 0 0 0 4 0 3 3 0 0 0 4 0 3 3 0 0 0 4 0c.293.26.632.464 1 .6zm2-11a1 1 0 1 1-2 0V7h2zM4.3 3H20a1 1 0 1 0 0-2H4.3a1 1 0 1 0 0 2z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <x-shopper-input.text wire:model="shop_name" id="shop_name" type="text" class="pl-10" autocomplete="off" />
                            </x-shopper-input.group>
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <x-shopper-input.group label="Email address" for="shop_email" isRequired :error="$errors->first('shop_email')" helpText="Your customers will use this address if they need to contact you.">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-secondary-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                    </svg>
                                </div>
                                <x-shopper-input.text wire:model="shop_email" id="shop_email" type="email" class="pl-10" autocomplete="off" />
                            </x-shopper-input.group>
                        </div>

                        <div wire:ignore x-data="internationalNumber('#phone_number')" class="col-span-6 sm:col-span-3">
                            <x-shopper-input.group label="Phone number" for="phone_number" :error="$errors->first('shop_phone_number')" helpText="Your customers will use this phone number if they need to call you directly.">
                                <x-shopper-input.text wire:model="shop_phone_number" id="phone_number" type="tel" class="pr-10" autocomplete="off" />
                                @error('shop_phone_number')
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

    <div class="hidden sm:block">
        <div class="py-5">
            <div class="border-t border-secondary-200 dark:border-secondary-700"></div>
        </div>
    </div>

    <div class="mt-10 sm:mt-0">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-semibold leading-6 text-secondary-900 dark:text-white">{{ __('Assets') }}</h3>
                    <p class="mt-4 text-sm leading-5 text-secondary-500">
                        {{ __('The logo and cover image of your store that will be visible on your site. This assets will appear on your invoices.') }}
                    </p>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <div class="shadow bg-white dark:bg-secondary-800 rounded-md overflow-hidden">
                    <div class="px-4 py-5 sm:p-6">
                        <x-shopper-input.group label="Logo" for="logo" :error="$errors->first('shop_logo')" noShadow>
                            <x-shopper-input.avatar-upload wire:model="shop_logo" id="photo">
                                <span class="h-12 w-12 rounded-full overflow-hidden bg-secondary-100 dark:bg-secondary-700 flex items-center justify-center">
                                    @if ($shop_logo)
                                        <img class="h-12 w-12 object-cover" src="{{ $shop_logo->temporaryUrl() }}" alt="Store logo">
                                    @elseif($logo)
                                        <img class="h-12 w-12 object-cover" src="{{ shopper_asset($logo) }}" alt="Store logo">
                                    @else
                                        <svg class="h-8 w-8 text-secondary-300 dark:text-secondary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    @endif
                                </span>
                            </x-shopper-input.avatar-upload>
                        </x-shopper-input.group>

                        <div class="mt-6">
                            <x-shopper-label value="{{ __('Cover photo') }}" />
                            <div class="mt-1">
                                @if($shop_cover)
                                    <div>
                                        <div class="shadow flex-shrink-0 rounded-md overflow-hidden">
                                            <img class="h-40 w-full object-cover" src="{{ $shop_cover->temporaryUrl() }}" alt="">
                                        </div>
                                        <div class="mt-1 flex items-center">
                                            <button wire:click="removeCover" type="button" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs leading-4 font-medium rounded text-red-700 bg-red-100 hover:bg-red-50 focus:outline-none focus:border-red-300 focus:shadow-outline-red active:bg-red-200 transition ease-in-out duration-150">
                                                <svg class="h-5 w-5 mr-1.5" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                {{ __('Remove') }}
                                            </button>
                                        </div>
                                    </div>
                                @elseif($cover)
                                    <div>
                                        <div class="shadow flex-shrink-0 rounded-md overflow-hidden">
                                            <img class="h-40 w-full object-cover" src="{{ shopper_asset($cover) }}" alt="Store Cover image">
                                        </div>
                                        <div class="mt-1 flex items-center">
                                            <button wire:click="deleteCover" type="button" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs leading-4 font-medium rounded text-red-700 bg-red-100 hover:bg-red-50 focus:outline-none focus:border-red-300 focus:shadow-outline-red active:bg-red-200 transition ease-in-out duration-150">
                                                <svg class="h-5 w-5 mr-1.5" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                {{ __('Delete') }}
                                            </button>
                                        </div>
                                    </div>
                                @else
                                    <div class="w-full">
                                        <label for="shop_cover" class="group mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-secondary-300 dark:border-secondary-700 border-dashed rounded-md cursor-pointer">
                                            <div class="text-center">
                                                <svg class="mx-auto h-12 w-12 text-secondary-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                                <p class="mt-1 text-sm text-secondary-500 dark:text-secondary-400">
                                                    <span class="font-medium text-primary-600 group-hover:text-primary-500 focus:outline-none focus:underline transition duration-150 ease-in-out">
                                                        {{ __('Upload a file') }}
                                                    </span>
                                                    {{ __('or drag and drop') }}
                                                </p>
                                                <p class="mt-1 text-xs text-secondary-500 dark:text-secondary-400">
                                                    PNG, JPG, GIF up to 1MB
                                                </p>
                                                <input id="shop_cover" type="file" wire:model="shop_cover" class="sr-only">
                                            </div>
                                        </label>
                                    </div>
                                @endif
                            </div>
                            @error('shop_cover')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
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
                    <h3 class="text-lg font-semibold leading-6 text-secondary-900 dark:text-white">{{ __('Store address') }}</h3>
                    <p class="mt-4 text-sm leading-5 text-secondary-500">
                        {{ __('This address will appear on your invoices. You can edit the address used.') }}
                    </p>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <div class="shadow bg-white dark:bg-secondary-800 rounded-md">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="grid grid-cols-6 gap-6">
                            <div class="col-span-6">
                                <x-shopper-input.group label="Legal name of the company" for="shop_legal_name" isRequired :error="$errors->first('shop_legal_name')">
                                    <x-shopper-input.text wire:model="shop_legal_name" id="shop_legal_name" type="text" autocomplete="off" placeholder="ShopStation LLC" />
                                </x-shopper-input.group>
                            </div>

                            <div class="col-span-6">
                                <x-shopper-input.group label="About" for="about">
                                    <livewire:shopper-forms.trix :value="$shop_about" />
                                </x-shopper-input.group>
                            </div>

                            <div class="col-span-6">
                                <x-shopper-input.group label="Address" for="shop_street_address">
                                    <x-shopper-input.text wire:model="shop_street_address" id="shop_street_address" type="text" autocomplete="off" placeholder="Akwa Avenue 34..." />
                                </x-shopper-input.group>
                            </div>

                            <div class="col-span-6">
                                <x-shopper-input.group label="Country" for="shop_country_id">
                                    <x-select
                                        :placeholder="__('Choose a Country')"
                                        wire:model="shop_country_id"
                                    >
                                        @foreach($countries as $country)
                                            <x-select.option :label="$country->name" :value="$country->id" />
                                        @endforeach
                                    </x-select>
                                </x-shopper-input.group>
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <x-shopper-input.group label="City" for="shop_city">
                                    <x-shopper-input.text wire:model="shop_city" id="shop_city" type="text" autocomplete="off" />
                                </x-shopper-input.group>
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <x-shopper-input.group label="Postal / Zip code" for="shop_zipcode">
                                    <x-shopper-input.text wire:model="shop_zipcode" id="shop_zipcode" type="text" autocomplete="off" />
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
            <div class="border-t border-secondary-200 dark:border-secondary-700"></div>
        </div>
    </div>

    <div class="mt-10 sm:mt-0">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-semibold leading-6 text-secondary-900 dark:text-white">{{ __('Store currency') }}</h3>
                    <p class="mt-4 text-sm leading-5 text-secondary-500">
                        {{ __("This is the currency your products are sold in. After your first sale, currency is locked in and can’t be changed.") }}
                    </p>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <div class="shadow bg-white dark:bg-secondary-800 rounded-md">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="grid grid-cols-6 gap-6">
                            <div class="col-span-6">
                                <x-shopper-input.group label="Store Currency" for="shop_currency_id">
                                    <x-select
                                        :placeholder="__('Choose currency')"
                                        wire:model="shop_currency_id"
                                    >
                                        @foreach($currencies as $currency)
                                            <x-select.option :label="$currency->name . ' (' . $currency->code . ')'" :value="$currency->id" />
                                        @endforeach
                                    </x-select>
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
            <div class="border-t border-secondary-200 dark:border-secondary-700"></div>
        </div>
    </div>

    <div class="mt-10 sm:mt-0">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-semibold leading-6 text-secondary-900 dark:text-white">{{ __('Social links') }}</h3>
                    <p class="mt-4 text-sm leading-5 text-secondary-500">
                        {{ __('Information about your different accounts on social networks. Users will be able to contact you directly on your official pages.') }}
                    </p>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <div class="shadow bg-white dark:bg-secondary-800 rounded-md overflow-hidden">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="grid grid-cols-6 gap-6">
                            <x-shopper-input.group class="col-span-6" label="Facebook" for="shop_facebook_link">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-5 h-5 text-secondary-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M20 10c0-5.523-4.477-10-10-10S0 4.477 0 10c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V10h2.54V7.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V10h2.773l-.443 2.89h-2.33v6.988C16.343 19.128 20 14.991 20 10z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <x-shopper-input.text wire:model="shop_facebook_link" id="shop_facebook_link" type="text" class="pl-10" autocomplete="off" placeholder="https://facebook.com/laravelshopper" />
                            </x-shopper-input.group>

                            <x-shopper-input.group class="col-span-6 sm:col-span-3" label="Instagram" for="shop_instagram_link">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-secondary-400" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.34 5.46a1.2 1.2 0 1 0 1.2 1.2 1.2 1.2 0 0 0-1.2-1.2zm4.6 2.42a7.59 7.59 0 0 0-.46-2.43 4.94 4.94 0 0 0-1.16-1.77 4.7 4.7 0 0 0-1.77-1.15 7.3 7.3 0 0 0-2.43-.47C15.06 2 14.72 2 12 2s-3.06 0-4.12.06a7.3 7.3 0 0 0-2.43.47 4.78 4.78 0 0 0-1.77 1.15 4.7 4.7 0 0 0-1.15 1.77 7.3 7.3 0 0 0-.47 2.43C2 8.94 2 9.28 2 12s0 3.06.06 4.12a7.3 7.3 0 0 0 .47 2.43 4.7 4.7 0 0 0 1.15 1.77 4.78 4.78 0 0 0 1.77 1.15 7.3 7.3 0 0 0 2.43.47C8.94 22 9.28 22 12 22s3.06 0 4.12-.06a7.3 7.3 0 0 0 2.43-.47 4.7 4.7 0 0 0 1.77-1.15 4.85 4.85 0 0 0 1.16-1.77 7.59 7.59 0 0 0 .46-2.43c0-1.06.06-1.4.06-4.12s0-3.06-.06-4.12zM20.14 16a5.61 5.61 0 0 1-.34 1.86 3.06 3.06 0 0 1-.75 1.15 3.19 3.19 0 0 1-1.15.75 5.61 5.61 0 0 1-1.86.34c-1 .05-1.37.06-4 .06s-3 0-4-.06a5.73 5.73 0 0 1-1.94-.3 3.27 3.27 0 0 1-1.1-.75 3 3 0 0 1-.74-1.15 5.54 5.54 0 0 1-.4-1.9c0-1-.06-1.37-.06-4s0-3 .06-4a5.54 5.54 0 0 1 .35-1.9A3 3 0 0 1 5 5a3.14 3.14 0 0 1 1.1-.8A5.73 5.73 0 0 1 8 3.86c1 0 1.37-.06 4-.06s3 0 4 .06a5.61 5.61 0 0 1 1.86.34 3.06 3.06 0 0 1 1.19.8 3.06 3.06 0 0 1 .75 1.1 5.61 5.61 0 0 1 .34 1.9c.05 1 .06 1.37.06 4s-.01 3-.06 4zM12 6.87A5.13 5.13 0 1 0 17.14 12 5.12 5.12 0 0 0 12 6.87zm0 8.46A3.33 3.33 0 1 1 15.33 12 3.33 3.33 0 0 1 12 15.33z" />
                                    </svg>
                                </div>
                                <x-shopper-input.text wire:model="shop_instagram_link" id="shop_instagram_link" type="text" class="pl-10" autocomplete="off" placeholder="https://instagram.com/laravelshopper" />
                            </x-shopper-input.group>

                            <x-shopper-input.group class="col-span-6 sm:col-span-3" label="Twitter" for="shop_twitter_link">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-secondary-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M6.29 18.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0020 3.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.073 4.073 0 01.8 7.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 010 16.407a11.616 11.616 0 006.29 1.84" />
                                    </svg>
                                </div>
                                <x-shopper-input.text wire:model="shop_twitter_link" id="shop_twitter_link" type="text" class="pl-10" autocomplete="off" placeholder="https://twitter.com/laravelshopper" />
                            </x-shopper-input.group>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6 border-t border-secondary-200 dark:border-secondary-700 pt-5 pb-10">
        <div class="flex justify-end">
            <x-shopper-button wire:click="store" type="button" wire:loading.attr="disabled">
                <x-shopper-loader wire:loading wire:target="store" class="text-white" />
                {{ __('Update information') }}
            </x-shopper-button>
        </div>
    </div>

</div>
