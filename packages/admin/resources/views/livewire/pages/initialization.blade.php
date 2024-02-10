<div class="min-h-screen bg-secondary-50 dark:bg-secondary-950">
    <div class="relative">
        <div class="relative isolate overflow-hidden border-b border-secondary-200 dark:border-secondary-800">
            <svg class="absolute inset-0 -z-10 h-full w-full stroke-secondary-200 dark:stroke-secondary-800 [mask-image:radial-gradient(64rem_64rem_at_top,white,transparent)]" aria-hidden="true">
                <defs>
                    <pattern id="55d3d46d-692e-45f2-becd-d8bdc9344f45" width="180" height="75" x="50%" y="0" patternUnits="userSpaceOnUse">
                        <path d="M.5 200V.5H200" fill="none" />
                    </pattern>
                </defs>
                <svg x="50%" y="0" class="overflow-visible fill-secondary-50 dark:fill-secondary-700">
                    <path d="M-200.5 0h201v201h-201Z M599.5 0h201v201h-201Z M399.5 400h201v201h-201Z M-400.5 600h201v201h-201Z" stroke-width="0" />
                </svg>
                <rect width="100%" height="100%" stroke-width="0" fill="url(#55d3d46d-692e-45f2-becd-d8bdc9344f45)" />
            </svg>
            <div class="absolute inset-x-0 top-10 -z-10 flex transform-gpu justify-center overflow-hidden blur-3xl" aria-hidden="true">
                <div
                    class="aspect-[1108/632] w-[69.25rem] flex-none bg-gradient-to-r from-primary-400 to-primary-600 dark:from-primary-200 dark:to-primary-500 opacity-20"
                    style="clip-path: polygon(73.6% 51.7%, 91.7% 11.8%, 100% 46.4%, 97.4% 82.2%, 92.5% 84.9%, 75.7% 64%, 55.3% 47.5%, 46.5% 49.4%, 45% 62.9%, 50.3% 87.2%, 21.3% 64.1%, 0.1% 100%, 5.4% 51.1%, 21.4% 63.9%, 58.9% 0.2%, 73.6% 51.7%)"
                ></div>
            </div>
            <nav role="navigation" id="navbar" class="py-6">
                <div class="mx-auto max-w-4xl px-4 lg:px-8">
                    <div class="flex justify-end">
                        <div class="py-1 rounded-lg bg-white shadow-xs dark:bg-secondary-800">
                            <button
                                type="button"
                                class="w-full inline-flex items-center px-4 py-2 text-sm leading-5 text-secondary-700 focus:outline-none focus:bg-secondary-100 dark:text-secondary-300 dark:focus:bg-secondary-900"
                                onclick="document.getElementById('logout-form').submit();"
                            >
                                <x-untitledui-log-out class="w-5 h-5 mr-2" />
                                {{ __('shopper::layout.account_dropdown.sign_out') }}
                            </button>
                            <form id="logout-form" action="{{ route('shopper.logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </nav>
            <div class="py-12 lg:py-16"></div>
        </div>
        <div class="absolute inse-x-0 transform translate-x-1/2 right-1/2 -mt-8 lg:-mt-10">
            <div class="flex items-center justify-center bg-white h-16 w-16 rounded-full shrink-0 lg:h-20 lg:w-20">
                <x-shopper::brand class="h-8 w-auto lg:h-9" />
            </div>
        </div>
    </div>

    <main class="mx-auto max-w-4xl px-4 py-12 sm:px-6 sm:py-20 lg:px-8">
        <h1 class="font-heading text-2xl font-bold leading-8 text-secondary-900 lg:text-3xl dark:text-white">
            {{ __('shopper::pages/settings.initialization.shop_configuration') }}
        </h1>
        <form wire:submit="store" class="divide-y divide-secondary-200 dark:divide-secondary-800">
            {{ $this->form }}
        {{--<div class="py-10">
            <div class="flex items-start">
                <x-untitledui-heading-02
                    class="h-10 w-10 text-secondary-400 dark:text-secondary-500"
                    aria-hidden="true"
                    stroke-width="1"
                />
                <div class="ml-4 flex-1">
                    <span class="text-sm font-medium text-primary-600">
                        {{ __('shopper::pages/settings.initialization.step_1') }}
                    </span>
                    <h3 class="mt-2 font-medium text-secondary-900 dark:text-white">
                        {{ __('shopper::pages/settings.initialization.tell_about') }}
                    </h3>
                    <p class="mt-4 text-sm leading-6 text-secondary-500 lg:max-w-2xl dark:text-secondary-300">
                        {{ __('shopper::pages/settings.initialization.step_1_description') }}
                    </p>
                </div>
            </div>
            <div class="mt-8 lg:pl-12">
                <x-shopper::card class="space-y-6 p-4 lg:p-6">
                    <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start">
                        <x-shopper::label for="name" class="sm:mt-px sm:pt-2" is-required>
                            {{ __('shopper::layout.forms.label.store_name') }}
                        </x-shopper::label>
                        <div class="mt-1 sm:mt-0 sm:col-span-2">
                            <div class="relative rounded-md shadow-sm sm:max-w-xs lg:max-w-lg">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <x-untitledui-shop class="w-5 h-5 text-secondary-400 dark:text-secondary-500" />
                                </div>
                                <x-shopper::forms.input id="name" type="text" wire:model.defer="shop_name" class="pl-10" autocomplete="name" autofocus required />
                            </div>
                        </div>
                    </div>

                    <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-secondary-200 sm:pt-5 dark:border-secondary-700">
                        <x-shopper::label for="email" class="sm:mt-px sm:pt-2" is-required>
                            {{ __('shopper::layout.forms.label.email') }}
                        </x-shopper::label>
                        <div class="mt-1 sm:mt-0 sm:col-span-2">
                            <div class="relative rounded-md shadow-sm sm:max-w-xs lg:max-w-lg">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <x-untitledui-mail class="w-5 h-5 text-secondary-400 dark:text-secondary-500" />
                                </div>
                                <x-shopper::forms.input wire:model.defer="shop_email" id="email" type="email" class="pl-10" required />
                            </div>
                        </div>
                    </div>

                    <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-secondary-200 sm:pt-5 dark:border-secondary-700">
                        <x-shopper::label for="country" class="sm:mt-px sm:pt-2" is-required>
                            {{ __('shopper::layout.forms.label.country') }}
                        </x-shopper::label>
                        <div class="relative mt-1 sm:mt-0 sm:col-span-2">
                            <div class="rounded-md shadow-sm sm:max-w-xs lg:max-w-lg" wire:ignore>
                                <select
                                    wire:model.defer="shop_country_id"
                                    id="shop_country_id"
                                    x-data="{}"
                                    x-init="function() { tomSelect($el, {}) }"
                                    autocomplete="off"
                                >
                                    @foreach($countries as $country)
                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 sm:mt-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-secondary-200 sm:pt-5 dark:border-secondary-700">
                        <x-shopper::label for="about" class="sm:mt-px sm:pt-2" :value="__('shopper::layout.forms.label.about')" />
                        <div class="mt-1 sm:mt-0 sm:col-span-2">
                            <div class="flex overflow-x-auto rounded-md shadow-sm sm:max-w-lg lg:w-full lg:overflow-visible">
                                <livewire:shopper-forms.trix :value="$shop_about" />
                            </div>
                            <p class="mt-2 text-sm text-secondary-500 dark:text-secondary-400">
                                {{ __('shopper::pages/settings.about_description') }}
                            </p>
                        </div>
                    </div>

                    <div class="sm:grid sm:grid-cols-3 sm:gap-4 sm:items-start sm:border-t sm:border-secondary-200 sm:pt-5 dark:border-secondary-700">
                        <x-shopper::label for="currency" class="sm:mt-px sm:pt-2" :value="__('shopper::layout.forms.label.currency')" />
                        <div class="mt-1 sm:mt-0 sm:col-span-2">
                            <div class="rounded-md shadow-sm sm:max-w-xs lg:max-w-lg" wire:ignore>
                                <select
                                    wire:model.defer="shop_currency_id"
                                    id="currency"
                                    x-data
                                    x-init="function() { tomSelect($el, {}) }"
                                    autocomplete="off"
                                >
                                    @foreach($currencies as $currency)
                                        <option value="{{ $currency->id }}">{{ $currency->name . ' (' . $currency->code . ')' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <p class="mt-2 text-sm text-secondary-500 dark:text-secondary-400">
                                {{ __('shopper::pages/settings.currency_description') }}
                            </p>
                        </div>
                    </div>
                </x-shopper::card>
            </div>
        </div>

        <div class="py-10">
            <div class="flex items-start">
                <x-untitledui-globe-05
                    class="h-10 w-10 text-secondary-400 dark:text-secondary-500"
                    aria-hidden="true"
                    stroke-width="1"
                />
                <div class="ml-4 flex-1">
                    <span class="text-sm font-medium text-primary-600">
                        {{ __('shopper::pages/settings.initialization.step_2') }}
                    </span>
                    <h3 class="mt-2 font-bold text-secondary-900 dark:text-white">
                        {{ __('shopper::pages/settings.initialization.address_description') }}
                    </h3>
                    <p class="mt-4 text-sm leading-6 text-secondary-500 lg:max-w-2xl dark:text-secondary-300">
                        {{ __('shopper::pages/settings.initialization.step_2_description') }}
                    </p>
                </div>
            </div>
            <div class="mt-8 lg:pl-12">
                <x-shopper::card class="space-y-6 p-4 lg:p-6">
                    <x-shopper::forms.group class="sm:col-span-4" :label="__('shopper::layout.forms.label.street_address')" for="street_address" isRequired>
                        <x-shopper::forms.input wire:model.defer="shop_street_address" id="street_address" type="text" autocomplete="off" placeholder="Akwa Avenue 34..." />
                    </x-shopper::forms.group>

                    <x-shopper::forms.group class="sm:col-span-2" :label="__('shopper::layout.forms.label.postal_code')" for="zipcode" isRequired>
                        <x-shopper::forms.input wire:model.defer="shop_zipcode" id="zipcode" type="text" autocomplete="off" placeholder="00237" required />
                    </x-shopper::forms.group>

                    <x-shopper::forms.group class="sm:col-span-2" :label="__('shopper::layout.forms.label.city')" for="city" isRequired>
                        <x-shopper::forms.input wire:model.defer="shop_city" id="city" type="text" autocomplete="off" required />
                    </x-shopper::forms.group>

                    <div wire:ignore x-data="internationalNumber('#phone_number')" class="sm:col-span-4">
                        <x-shopper::label for="phone_number" class="sm:mt-px sm:pt-2" :value="__('shopper::layout.forms.label.phone_number')" />
                        <div class="mt-1 sm:mt-0 sm:col-span-2">
                            <div class="relative rounded-md shadow-sm sm:max-w-xs lg:max-w-lg">
                                <x-shopper::forms.input wire:model.defer="shop_phone_number" id="phone_number" type="tel" class="w-full" autocomplete="off" />
                            </div>
                        </div>
                    </div>
                </x-shopper::card>
            </div>
        </div>

        <div class="py-10">
            <div class="flex items-start">
                <x-untitledui-share-07
                    class="h-10 w-10 text-secondary-400 dark:text-secondary-500"
                    aria-hidden="true"
                    stroke-width="1"
                />
                <div class="ml-4 flex-1">
                    <span class="text-sm font-medium text-primary-600 dark:text-primary-500">
                        {{ __('shopper::pages/settings.initialization.step_3') }}
                    </span>
                    <h3 class="mt-2 font-bold text-secondary-900 dark:text-white">
                        {{ __('shopper::pages/settings.initialization.social_description') }}
                        <span class="font-normal text-secondary-500 dark:text-secondary-400">
                            ({{ __('shopper::layout.forms.label.optional') }})
                        </span>
                    </h3>
                    <p class="mt-4 text-sm leading-6 text-secondary-500 lg:max-w-2xl dark:text-secondary-300">
                        {{ __('shopper::pages/settings.initialization.step_3_description') }}
                    </p>
                </div>
            </div>
            <div class="mt-8 lg:pl-12">
                <x-shopper::card class="space-y-6 p-4 lg:p-6">
                    <x-shopper::forms.group class="col-span-6 lg:col-span-2" :label="__('shopper::words.socials.facebook')" for="facebook">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <x-shopper::icons.facebook class="w-5 h-5 text-secondary-400 dark:text-secondary-500" />
                        </div>
                        <x-shopper::forms.input wire:model.defer="shop_facebook_link" id="facebook" type="text" class="pl-10" autocomplete="off" placeholder="https://facebook.com/laravelshopper" />
                    </x-shopper::forms.group>
                    <x-shopper::forms.group class="col-span-6 lg:col-span-2" :label="__('shopper::words.socials.instagram')" for="instagram">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <x-shopper::icons.instagram class="w-5 h-5 text-secondary-400 dark:text-secondary-500" />
                        </div>
                        <x-shopper::forms.input wire:model.defer="shop_instagram_link" id="instagram" type="text" class="pl-10" autocomplete="off" placeholder="https://instagram.com/laravelshopper" />
                    </x-shopper::forms.group>
                    <x-shopper::forms.group class="col-span-6 lg:col-span-2" :label="__('shopper::words.socials.twitter')" for="twitter">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <x-shopper::icons.twitter class="w-5 h-5 text-secondary-400 dark:text-secondary-500" />
                        </div>
                        <x-shopper::forms.input wire:model.defer="shop_twitter_link" id="twitter" type="text" class="pl-10" autocomplete="off" placeholder="https://twitter.com/laravelshopper" />
                    </x-shopper::forms.group>
                </x-shopper::card>
            </div>
        </div>

        <div class="pt-10">
            <div class="flex justify-end">
                <x-shopper::buttons.primary wire:click="store" type="button" wire:loading.attr="disabled">
                    <x-shopper::loader wire:loading wire:target="store" class="text-white" />
                    {{ __('shopper::pages/settings.initialization.action') }}
                </x-shopper::buttons.primary>
            </div>
        </div>--}}
        </form>
    </main>
</div>
