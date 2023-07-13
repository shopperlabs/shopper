<x-shopper::container>
    <x-shopper::breadcrumb :back="route('shopper.settings.index')" :current="__('General')">
        <x-heroicon-s-chevron-left class="shrink-0 h-4 w-4 text-secondary-300 dark:text-secondary-600" />
        <x-shopper::breadcrumb.link :link="route('shopper.settings.index')" :title="__('shopper::words.settings')" />
    </x-shopper::breadcrumb>

    <x-shopper::heading>
        <x-slot name="title">
            {{ __('shopper::pages/settings.settings.title') }}
        </x-slot>
    </x-shopper::heading>

    <x-shopper::validation-errors />

    <div class="mt-8 lg:grid lg:grid-cols-3 lg:gap-y-6 lg:gap-x-12">
        <div class="lg:col-span-1">
            <div>
                <h3 class="text-lg font-medium leading-6 text-secondary-900 dark:text-white font-display">
                    {{ __('shopper::pages/settings.settings.store_details') }}
                </h3>
                <p class="mt-2 text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                    {{ __('shopper::pages/settings.settings.store_detail_summary') }}
                </p>
            </div>
        </div>
        <div class="mt-5 lg:mt-0 lg:col-span-2">
            <div class="grid grid-cols-6 gap-6">
                <div class="col-span-6">
                    <x-shopper::forms.group :label="__('shopper::layout.forms.label.store_name')" for="shop_name" isRequired :error="$errors->first('shop_name')">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-5 h-5 text-secondary-400" fill="currentColor" viewBox="0 0 24 24">
                                <path fill-rule="evenodd" d="M22 5H2a1 1 0 0 0-1 1v4a3 3 0 0 0 2 2.82V22a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1v-9.18A3 3 0 0 0 23 10V6a1 1 0 0 0-1-1zm-7 2h2v3a1 1 0 1 1-2 0zm-4 0h2v3a1 1 0 1 1-2 0zM7 7h2v3a1 1 0 1 1-2 0zm-3 4a1 1 0 0 1-1-1V7h2v3a1 1 0 0 1-1 1zm10 10h-4v-2a2 2 0 1 1 4 0zm5 0h-3v-2a4 4 0 1 0-8 0v2H5v-8.18a3.17 3.17 0 0 0 1-.6 3 3 0 0 0 4 0 3 3 0 0 0 4 0 3 3 0 0 0 4 0c.293.26.632.464 1 .6zm2-11a1 1 0 1 1-2 0V7h2zM4.3 3H20a1 1 0 1 0 0-2H4.3a1 1 0 1 0 0 2z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <x-shopper::forms.input wire:model="shop_name" id="shop_name" type="text" class="pl-10 dark:bg-secondary-800 dark:border-transparent" autocomplete="off" />
                    </x-shopper::forms.group>
                </div>

                <div class="col-span-6 sm:col-span-3">
                    <x-shopper::forms.group
                        :label="__('shopper::layout.forms.label.email')"
                        for="shop_email"
                        isRequired
                        :error="$errors->first('shop_email')"
                        :helpText="__('shopper::pages/settings.settings.email_helper')"
                    >
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-5 h-5 text-secondary-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                            </svg>
                        </div>
                        <x-shopper::forms.input wire:model.defer="shop_email" id="shop_email" type="email" class="pl-10 dark:bg-secondary-800 dark:border-transparent" autocomplete="off" />
                    </x-shopper::forms.group>
                </div>

                <div wire:ignore x-data="internationalNumber('#phone_number')" class="col-span-6 sm:col-span-3">
                    <x-shopper::forms.group
                        for="phone_number"
                        :label="__('shopper::layout.forms.label.phone_number')"
                        :error="$errors->first('shop_phone_number')"
                        :helpText="__('shopper::pages/settings.settings.phone_number_helper')"
                    >
                        <x-shopper::forms.input wire:model.defer="shop_phone_number" id="phone_number" type="tel" class="pr-10 dark:bg-secondary-800 dark:border-transparent" autocomplete="off" />
                        @error('shop_phone_number')
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="w-5 h-5 text-danger-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        @enderror
                    </x-shopper::forms.group>
                </div>
            </div>
        </div>
    </div>

    <x-shopper::separator />

    <div class="mt-10 sm:mt-0">
        <div class="lg:grid lg:grid-cols-3 lg:gap-y-6 lg:gap-x-12">
            <div class="lg:col-span-1">
                <div>
                    <h3 class="text-lg font-medium leading-6 text-secondary-900 dark:text-white font-display">
                        {{ __('shopper::pages/settings.settings.assets') }}
                    </h3>
                    <p class="mt-2 text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                        {{ __('shopper::pages/settings.settings.assets_summary') }}
                    </p>
                </div>
            </div>
            <div class="mt-5 lg:mt-0 lg:col-span-2">
                <div>
                    <x-shopper::forms.group :label="__('shopper::layout.forms.label.logo')" for="logo" :error="$errors->first('logo')" noShadow>
                        <x-shopper::forms.avatar-upload wire:model.debounce.550ms="logo" id="photo">
                                <span class="flex items-center justify-center w-12 h-12 overflow-hidden rounded-full bg-secondary-100 dark:bg-secondary-700">
                                    @if ($logo)
                                        <img class="object-cover w-12 h-12" src="{{ $logo->temporaryUrl() }}" alt="Store logo">
                                    @elseif($shop_logo)
                                        <img class="object-cover w-12 h-12" src="{{ shopper_asset($shop_logo) }}" alt="Store logo">
                                    @else
                                        <svg class="w-8 h-8 text-secondary-300 dark:text-secondary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    @endif
                                </span>
                        </x-shopper::forms.avatar-upload>
                    </x-shopper::forms.group>

                    <div class="mt-6">
                        <x-shopper::label value="{{ __('shopper::layout.forms.label.cover_photo') }}" />
                        <div class="mt-1">
                            @if($cover)
                                <div>
                                    <div class="overflow-hidden rounded-md shadow shrink-0">
                                        <img class="object-cover w-full h-40" src="{{ $cover->temporaryUrl() }}" alt="">
                                    </div>
                                    <div class="flex items-center mt-1">
                                        <button wire:click="removeCover" type="button" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs leading-4 font-medium rounded text-danger-700 bg-danger-100 hover:bg-danger-50 focus:outline-none focus:border-danger-300 focus:shadow-outline-red active:bg-danger-200 transition ease-in-out duration-150">
                                            <svg class="h-5 w-5 mr-1.5" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                                <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            {{ __('shopper::layout.forms.actions.remove') }}
                                        </button>
                                    </div>
                                </div>
                            @elseif($shop_cover)
                                <div>
                                    <div class="overflow-hidden rounded-md shadow shrink-0">
                                        <img class="object-cover w-full h-40" src="{{ shopper_asset($shop_cover) }}" alt="Store Cover image">
                                    </div>
                                    <div class="flex items-center mt-1">
                                        <button wire:click="deleteCover" type="button" class="inline-flex items-center px-2.5 py-1.5 border border-transparent text-xs leading-4 font-medium rounded text-danger-700 bg-danger-100 hover:bg-danger-50 focus:outline-none focus:border-danger-300 focus:shadow-outline-red active:bg-danger-200 transition ease-in-out duration-150">
                                            <svg class="h-5 w-5 mr-1.5" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                                                <path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            {{ __('shopper::layout.forms.actions.delete') }}
                                        </button>
                                    </div>
                                </div>
                            @else
                                <div class="w-full">
                                    <label for="shop_cover" class="flex justify-center px-6 pt-5 pb-6 mt-2 border-2 border-dashed rounded-md cursor-pointer group border-secondary-300 dark:border-secondary-700">
                                        <div class="text-center">
                                            <svg class="w-12 h-12 mx-auto text-secondary-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <p class="mt-1 text-sm text-secondary-500 dark:text-secondary-400">
                                                <span class="font-medium transition duration-150 ease-in-out text-primary-600 group-hover:text-primary-500 focus:outline-none focus:underline">
                                                    {{ __('shopper::components.files.file') }}
                                                </span>
                                                {{ __('shopper::components.files.drag_n_drop') }}
                                            </p>
                                            <p class="mt-1 text-xs text-secondary-500 dark:text-secondary-400">
                                                {{ __('shopper::components.files.type_size', ['size' => 1]) }}
                                            </p>
                                            <input id="shop_cover" type="file" wire:model.defer="cover" class="sr-only">
                                        </div>
                                    </label>
                                </div>
                            @endif
                        </div>
                        @error('cover')
                            <p class="mt-2 text-sm text-danger-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-shopper::separator />

    <div class="mt-10 sm:mt-0">
        <div class="lg:grid lg:grid-cols-3 lg:gap-y-6 lg:gap-x-12">
            <div class="lg:col-span-1">
                <div>
                    <h3 class="text-lg font-medium leading-6 text-secondary-900 dark:text-white font-display">
                        {{ __('shopper::pages/settings.settings.store_address') }}
                    </h3>
                    <p class="mt-2 text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                        {{ __('shopper::pages/settings.settings.store_address_summary') }}
                    </p>
                </div>
            </div>
            <div class="mt-5 lg:mt-0 lg:col-span-2">
                <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-6">
                        <x-shopper::forms.group :label="__('shopper::layout.forms.label.legal_name')" for="shop_legal_name" isRequired :error="$errors->first('shop_legal_name')">
                            <x-shopper::forms.input wire:model.defer="shop_legal_name" id="shop_legal_name" class="dark:bg-secondary-800 dark:border-transparent" type="text" autocomplete="off" :placeholder="__('ShopStation LLC')" />
                        </x-shopper::forms.group>
                    </div>

                    <div class="col-span-6">
                        <x-shopper::forms.group :label="__('shopper::layout.forms.label.about')" for="about">
                            <livewire:shopper-forms.trix :value="$shop_about" />
                        </x-shopper::forms.group>
                    </div>

                    <div class="col-span-6">
                        <x-shopper::forms.group :label="__('shopper::layout.forms.label.street_address')" for="shop_street_address">
                            <x-shopper::forms.input wire:model.defer="shop_street_address" id="shop_street_address" class="dark:bg-secondary-800 dark:border-transparent" type="text" autocomplete="off" :placeholder="__('Akwa Avenue 34...')" />
                        </x-shopper::forms.group>
                    </div>

                    <div class="col-span-6">
                        <x-shopper::forms.group :label="__('shopper::layout.forms.label.country')" for="shop_country_id" wire:ignore>
                            <select
                                wire:model.defer="shop_country_id"
                                id="shop_country_id"
                                x-data="{}"
                                x-init="function() { tomSelect($el, {}) }"
                                autocomplete="off"
                                class="dark:bg-secondary-800 dark:border-transparent"
                            >
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}" @selected($shop_country_id === $country->id)>
                                        {{ $country->name }}
                                    </option>
                                @endforeach
                            </select>
                        </x-shopper::forms.group>
                    </div>

                    <div class="col-span-6 sm:col-span-3">
                        <x-shopper::forms.group :label="__('shopper::layout.forms.label.city')" for="shop_city">
                            <x-shopper::forms.input wire:model.defer="shop_city" id="shop_city" type="text" class="dark:bg-secondary-800 dark:border-transparent" autocomplete="off" />
                        </x-shopper::forms.group>
                    </div>

                    <div class="col-span-6 sm:col-span-3">
                        <x-shopper::forms.group :label="__('shopper::layout.forms.label.postal_code')" for="shop_zipcode">
                            <x-shopper::forms.input wire:model.defer="shop_zipcode" id="shop_zipcode" type="text" class="dark:bg-secondary-800 dark:border-transparent" autocomplete="off" />
                        </x-shopper::forms.group>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-shopper::separator />

    <div class="mt-10 sm:mt-0">
        <div class="lg:grid lg:grid-cols-3 lg:gap-y-6 lg:gap-x-12">
            <div class="lg:col-span-1">
                <div>
                    <h3 class="text-lg font-medium leading-6 text-secondary-900 dark:text-white font-display">
                        {{ __('shopper::pages/settings.settings.store_currency') }}
                    </h3>
                    <p class="mt-2 text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                        {{ __('shopper::pages/settings.settings.store_currency_summary') }}
                    </p>
                </div>
            </div>
            <div class="mt-5 lg:mt-0 lg:col-span-2">
                <div wire:ignore>
                    <x-shopper::forms.group :label="__('shopper::layout.forms.label.currency')" for="shop_currency_id">
                        <select
                            wire:model.defer="shop_currency_id"
                            id="shop_currency_id"
                            x-data="{}"
                            x-init="function() { tomSelect($el, {}) }"
                            autocomplete="off"
                            class="dark:bg-secondary-800 dark:border-transparent"
                        >
                            @foreach($currencies as $currency)
                                <option value="{{ $currency->id }}" @selected($currency->id === $shop_currency_id)>{{ $currency->name . ' (' . $currency->code . ')' }}</option>
                            @endforeach
                        </select>
                    </x-shopper::forms.group>
                </div>
            </div>
        </div>
    </div>

    <x-shopper::separator />

    <div class="mt-10 sm:mt-0">
        <div class="lg:grid lg:grid-cols-3 lg:gap-y-6 lg:gap-x-12">
            <div class="lg:col-span-1">
                <div>
                    <h3 class="text-lg font-medium leading-6 text-secondary-900 dark:text-white font-display">
                        {{ __('shopper::pages/settings.settings.social_links') }}
                    </h3>
                    <p class="mt-2 text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                        {{ __('shopper::pages/settings.settings.social_links_summary') }}
                    </p>
                </div>
            </div>
            <div class="mt-5 lg:mt-0 lg:col-span-2">
                <div class="grid grid-cols-6 gap-6">
                    <x-shopper::forms.group class="col-span-6" :label="__('shopper::words.socials.facebook')" for="shop_facebook_link">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <x-shopper::icons.facebook class="w-5 h-5 text-secondary-400" />
                        </div>
                        <x-shopper::forms.input wire:model.defer="shop_facebook_link" id="shop_facebook_link" type="text" class="pl-10 dark:bg-secondary-800 dark:border-transparent" autocomplete="off" placeholder="https://facebook.com/laravelshopper" />
                    </x-shopper::forms.group>

                    <x-shopper::forms.group class="col-span-6 sm:col-span-3" :label="__('shopper::words.socials.instagram')" for="shop_instagram_link">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <x-shopper::icons.instagram class="w-5 h-5 text-secondary-400" />
                        </div>
                        <x-shopper::forms.input wire:model.defer="shop_instagram_link" id="shop_instagram_link" type="text" class="pl-10 dark:bg-secondary-800 dark:border-transparent" autocomplete="off" placeholder="https://instagram.com/laravelshopper" />
                    </x-shopper::forms.group>

                    <x-shopper::forms.group class="col-span-6 sm:col-span-3" :label="__('shopper::words.socials.twitter')" for="shop_twitter_link">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <x-shopper::icons.twitter class="w-5 h-5 text-secondary-400" />
                        </div>
                        <x-shopper::forms.input wire:model.defer="shop_twitter_link" id="shop_twitter_link" type="text" class="pl-10 dark:bg-secondary-800 dark:border-transparent" autocomplete="off" placeholder="https://twitter.com/laravelshopper" />
                    </x-shopper::forms.group>
                </div>
            </div>
        </div>
    </div>

    <div class="pt-5 pb-10 mt-6 border-t border-secondary-200 dark:border-secondary-700">
        <div class="flex justify-end">
            <x-shopper::buttons.primary wire:click="store" type="button" wire:loading.attr="disabled">
                <x-shopper::loader wire:loading wire:target="store" class="text-white" />
                {{ __('shopper::pages/settings.settings.update_information') }}
            </x-shopper::buttons.primary>
        </div>
    </div>

</x-shopper::container>
