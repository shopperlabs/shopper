<div class="px-4 py-5 bg-white shadow overflow-hidden rounded-md sm:p-6 dark:bg-secondary-800">
    <div class="space-y-6 divide-y divide-secondary-200 dark:divide-secondary-700">
        <div class="space-y-1">
            <h3 class="text-lg leading-6 font-medium text-secondary-900 dark:text-white">
                {{ __('Profile') }}
            </h3>
            <p class="max-w-2xl text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                {{ __("All your customer's public information can be found here.") }}
            </p>
        </div>
        <div>
            <dl class="divide-y divide-secondary-200 dark:divide-secondary-700">
                <div x-data="{ open: @entangle('firstNameUpdate') }" class="py-4 space-y-1 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm leading-5 font-medium text-secondary-500 dark:text-secondary-400">
                        {{ __('First name') }}
                    </dt>
                    <dd class="flex space-x-4 text-sm leading-5 text-secondary-900 sm:mt-0 sm:col-span-2 dark:text-white">
                        <div class="grow">
                            <span x-show="!open">{{ $firstName }}</span>
                            <div x-show="open" style="display: none">
                                <div class="w-full sm:max-w-xs">
                                    <x-shopper-input.group :error="$errors->first('firstName')">
                                        <x-shopper-input.text wire:model.lazy="firstName" id="firstName" type="text" autocomplete="off" />
                                    </x-shopper-input.group>
                                </div>
                            </div>
                        </div>
                        <div class="shrink-0 ml-4">
                            <span x-show="!open">
                                <button @click="open = true" type="button" class="font-medium text-primary-600 hover:text-primary-500">
                                    {{ __('Update') }}
                                </button>
                            </span>
                            <span x-show="open" class="flex items-start space-x-4" style="display: none">
                                <button wire:click="saveFirstName" type="button" class="inline-flex items-center font-medium text-primary-600 hover:text-primary-500">
                                    <x-shopper-loader wire:loading wire:target="saveFirstName" class="text-primary-600" />
                                    <span class="ml-1.5">{{ __('Save') }}</span>
                                </button>
                                <span class="text-secondary-300 dark:text-secondary-700" aria-hidden="true">|</span>
                                <button x-on:click="open = false" type="button" class="font-medium text-primary-600 hover:text-primary-500">
                                    {{ __('Cancel') }}
                                </button>
                            </span>
                        </div>
                    </dd>
                </div>
                <div x-data="{ open: @entangle('lastNameUpdate') }" class="py-4 space-y-1 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:pt-5">
                    <dt class="text-sm leading-5 font-medium text-secondary-500 dark:text-secondary-400">
                        {{ __('Last name') }}
                    </dt>
                    <dd class="flex space-x-4 text-sm leading-5 text-secondary-900 sm:mt-0 sm:col-span-2 dark:text-white">
                        <div class="grow">
                            <span x-show="!open">{{ $lastName }}</span>
                            <div x-show="open" style="display: none">
                                <div class="w-full sm:max-w-xs">
                                    <x-shopper-input.group :error="$errors->first('lastName')">
                                        <x-shopper-input.text wire:model.lazy="lastName" id="lastName" type="text" autocomplete="off" />
                                    </x-shopper-input.group>
                                </div>
                            </div>
                        </div>
                        <div class="shrink-0 ml-4">
                            <span x-show="!open">
                                <button @click="open = true" type="button" class="font-medium text-primary-600 hover:text-primary-500">
                                    {{ __('Update') }}
                                </button>
                            </span>
                            <span x-show="open" class="flex items-start space-x-4" style="display: none">
                                <button wire:click="saveLastName" type="button" class="inline-flex items-center font-medium text-primary-600 hover:text-primary-500">
                                    <x-shopper-loader wire:loading wire:target="saveLastName" class="text-primary-600" />
                                    <span class="ml-1.5">{{ __('Save') }}</span>
                                </button>
                                <span class="text-secondary-300 dark:text-secondary-700" aria-hidden="true">|</span>
                                <button x-on:click="open = false" type="button" class="font-medium text-primary-600 hover:text-primary-500">
                                    {{ __('Cancel') }}
                                </button>
                            </span>
                        </div>
                    </dd>
                </div>
                <div class="py-4 space-y-1 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:pt-5">
                    <dt class="text-sm leading-5 font-medium text-secondary-500 dark:text-secondary-400">
                        {{ __('Photo') }}
                    </dt>
                    <dd class="flex space-x-4 text-sm leading-5 text-secondary-900 sm:mt-0 sm:col-span-2 dark:text-white">
                    <span class="grow">
                        <img class="h-8 w-8 rounded-full" src="{{ $customer->picture }}" alt="">
                    </span>
                    </dd>
                </div>
                <div x-data="{ open: @entangle('emailUpdate') }" class="py-4 space-y-1 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:pt-5">
                    <dt class="text-sm leading-5 font-medium text-secondary-500 dark:text-secondary-400">
                        {{ __('Email address') }}
                    </dt>
                    <dd class="flex space-x-4 text-sm leading-5 text-secondary-900 sm:mt-0 sm:col-span-2 dark:text-white">
                        <div class="grow">
                            <span x-show="!open">{{ $email }}</span>
                            <div x-show="open" style="display: none">
                                <div class="w-full sm:max-w-xs">
                                    <x-shopper-input.group :error="$errors->first('email')">
                                        <x-shopper-input.text wire:model.debounce.350ms="email" id="email" type="text" autocomplete="off" />
                                    </x-shopper-input.group>
                                </div>
                            </div>
                        </div>
                        <div class="shrink-0 ml-4">
                            <span x-show="!open">
                                <button @click="open = true" type="button" class="font-medium text-primary-600 hover:text-primary-500">
                                    {{ __('Update') }}
                                </button>
                            </span>
                            <span x-show="open" class="flex items-start space-x-4" style="display: none">
                                <button wire:click="saveEmail" type="button" class="inline-flex items-center font-medium text-primary-600 hover:text-primary-500">
                                    <x-shopper-loader wire:loading wire:target="saveEmail" class="text-primary-600" />
                                    <span class="ml-1.5">{{ __('Save') }}</span>
                                </button>
                                <span class="text-secondary-300 dark:text-secondary-700" aria-hidden="true">|</span>
                                <button x-on:click="$wire.cancelEmail()" type="button" class="font-medium text-primary-600 hover:text-primary-500">
                                    {{ __('Cancel') }}
                                </button>
                            </span>
                        </div>
                    </dd>
                </div>
                <div x-data="{ open: @entangle('birthDateUpdate') }" class="py-4 space-y-1 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:pt-5">
                    <dt class="text-sm leading-5 font-medium text-secondary-500 dark:text-secondary-400">
                        {{ __('Birth Date') }}
                    </dt>
                    <dd class="flex space-x-4 text-sm leading-5 text-secondary-900 sm:mt-0 sm:col-span-2 dark:text-white">
                        <div class="grow">
                            <p x-show="!open" class="flex items-center">
                                <x-heroicon-o-cake class="w-5 h-5 -ml-1 mr-2.5 text-secondary-500 dark:text-secondary-400" />
                                <span>{{ $birthDateFormatted }}</span>
                            </p>
                            <div x-show="open" style="display: none">
                                <div class="w-full sm:max-w-xs relative rounded-md shadow-sm">
                                    <x-datetime-picker :placeholder="__('1970-01-01')" wire:model.lazy="birthDate" parse-format="YYYY-MM-DD" :without-time="true" />
                                </div>
                            </div>
                        </div>
                        <div class="shrink-0 ml-4">
                            <span x-show="!open">
                                <button @click="open = true" type="button" class="font-medium text-primary-600 hover:text-primary-500">
                                    {{ __('Update') }}
                                </button>
                            </span>
                            <span x-show="open" class="flex items-start space-x-4" style="display: none">
                                <button wire:click="saveBirthDate" type="button" class="inline-flex items-center font-medium text-primary-600 hover:text-primary-500">
                                    <x-shopper-loader wire:loading wire:target="saveBirthDate" class="text-primary-600" />
                                    <span class="ml-1.5">{{ __('Save') }}</span>
                                </button>
                                <span class="text-secondary-300 dark:text-secondary-700" aria-hidden="true">|</span>
                                <button @click="open = false" type="button" class="font-medium text-primary-600 hover:text-primary-500">
                                    {{ __('Cancel') }}
                                </button>
                            </span>
                        </div>
                    </dd>
                </div>
                <div x-data="{ open: @entangle('genderUpdate') }" class="py-4 space-y-1 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:border-b sm:border-secondary-200">
                    <dt class="text-sm leading-5 font-medium text-secondary-500 dark:text-secondary-400">
                        {{ __('Gender') }}
                    </dt>
                    <dd class="flex items-center space-x-4 text-sm leading-5 text-secondary-900 sm:mt-0 sm:col-span-2 dark:text-white">
                        <div class="grow">
                            <span x-show="!open" class="capitalize">{{ $gender }}</span>
                            <div x-show="open" style="display: none">
                                <div class="w-full sm:max-w-xs">
                                    <x-shopper-input.select wire:model.lazy="gender" aria-label="{{ __('Gender') }}">
                                        <option value="male">{{ __('Male') }}</option>
                                        <option value="female">{{ __('Female') }}</option>
                                    </x-shopper-input.select>
                                </div>
                            </div>
                        </div>
                        <div class="shrink-0 ml-4">
                            <span x-show="!open">
                                <button @click="open = true" type="button" class="font-medium text-primary-600 hover:text-primary-500">
                                    {{ __('Update') }}
                                </button>
                            </span>
                            <span x-show="open" class="flex items-start space-x-4" style="display: none">
                                <button wire:click="saveGender" type="button" class="inline-flex items-center font-medium text-primary-600 hover:text-primary-500">
                                    <x-shopper-loader wire:loading wire:target="saveGender" class="text-primary-600" />
                                    <span class="ml-1.5">{{ __('Save') }}</span>
                                </button>
                                <span class="text-secondary-300 dark:text-secondary-700" aria-hidden="true">|</span>
                                <button @click="open = false" type="button" class="font-medium text-primary-600 hover:text-primary-500">
                                    {{ __('Cancel') }}
                                </button>
                            </span>
                        </div>
                    </dd>
                </div>
            </dl>
        </div>
    </div>
    <div class="mt-10 space-y-6 divide-y divide-secondary-200 dark:divide-secondary-700">
        <div class="space-y-1">
            <h3 class="text-lg leading-6 font-medium text-secondary-900 dark:text-white">
                {{ __('Account') }}
            </h3>
            <p class="max-w-2xl text-sm leading-5 text-secondary-500 dark:text-secondary-400">
                {{ __('Manage how information is used on the customer account.') }}
            </p>
        </div>
        <div>
            <dl class="divide-y divide-secondary-200 dark:divide-secondary-700">
                <div class="py-4 space-y-1 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:pt-5">
                    <dt class="text-sm leading-5 font-medium text-secondary-500 dark:text-secondary-400">
                        {{ __('Email Marketing') }}
                    </dt>
                    <dd class="flex text-sm leading-5 text-secondary-900 sm:mt-0 sm:col-span-2">
                        <span role="checkbox" tabindex="0" x-on:click="on = !on" @keydown.space.prevent="on = !on" :aria-checked="on.toString()" aria-checked="true" x-data="{ on: @entangle('optIn') }" x-bind:class="{ 'bg-secondary-200 dark:bg-secondary-700': !on, 'bg-primary-600': on }" class="relative inline-flex shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:shadow-outline sm:ml-auto bg-primary-600">
                            <span aria-hidden="true" x-bind:class="{ 'translate-x-5': on, 'translate-x-0': !on }" class="inline-block h-5 w-5 rounded-full bg-white shadow transform transition ease-in-out duration-200 translate-x-5"></span>
                        </span>
                    </dd>
                </div>
                <div class="py-4 space-y-1 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:border-b sm:border-secondary-200 sm:dark:border-secondary-700">
                    <dt class="text-sm leading-5 font-medium text-secondary-500 dark:text-secondary-400">
                        {{ __('Two-Factor Authentication') }}
                    </dt>
                    <dd class="flex text-sm leading-5 text-secondary-900 sm:mt-0 sm:col-span-2 dark:text-white">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold sm:ml-auto rounded-full {{ $hasEnabledTwoFactor ? 'bg-green-100 text-green-800': 'bg-secondary-100 text-secondary-800 dark:bg-secondary-700 dark:text-secondary-300' }}">
                            {{ $hasEnabledTwoFactor ? __('Enabled') : __('Disabled') }}
                        </span>
                    </dd>
                </div>
            </dl>
        </div>
    </div>
</div>
