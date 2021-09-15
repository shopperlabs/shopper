@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

<div class="px-4 py-5 bg-white shadow overflow-hidden rounded-md sm:p-6 dark:bg-gray-800">
    <div class="space-y-6 divide-y divide-gray-200 dark:divide-gray-700">
        <div class="space-y-1">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                {{ __('Profile') }}
            </h3>
            <p class="max-w-2xl text-sm leading-5 text-gray-500 dark:text-gray-400">
                {{ __("All your customer's public information can be found here.") }}
            </p>
        </div>
        <div>
            <dl class="divide-y divide-gray-200 dark:divide-gray-700">
                <div x-data="{ open: @entangle('firstNameUpdate') }" class="py-4 space-y-1 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm leading-5 font-medium text-gray-500 dark:text-gray-400">
                        {{ __('First name') }}
                    </dt>
                    <dd class="flex space-x-4 text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-2 dark:text-white">
                        <div class="flex-grow">
                            <span x-show="!open">{{ $firstName }}</span>
                            <div x-show="open" style="display: none">
                                <div class="w-full sm:max-w-xs">
                                    <x-shopper-input.group :error="$errors->first('firstName')">
                                        <x-shopper-input.text wire:model.lazy="firstName" id="firstName" type="text" autocomplete="off" />
                                    </x-shopper-input.group>
                                </div>
                            </div>
                        </div>
                        <div class="flex-shrink-0 ml-4">
                            <span x-show="!open">
                                <button @click="open = true" type="button" class="font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400">
                                    {{ __('Update') }}
                                </button>
                            </span>
                            <span x-show="open" class="flex items-start space-x-4" style="display: none">
                                <button wire:click="saveFirstName" type="button" class="inline-flex items-center font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400">
                                    <x-shopper-loader wire:loading wire:target="saveFirstName" class="text-blue-600" />
                                    <span class="ml-1.5">{{ __('Save') }}</span>
                                </button>
                                <span class="text-gray-300" aria-hidden="true">|</span>
                                <button x-on:click="open = false" type="button" class="font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400">
                                    {{ __('Cancel') }}
                                </button>
                            </span>
                        </div>
                    </dd>
                </div>
                <div x-data="{ open: @entangle('lastNameUpdate') }" class="py-4 space-y-1 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:pt-5">
                    <dt class="text-sm leading-5 font-medium text-gray-500 dark:text-gray-400">
                        {{ __('Last name') }}
                    </dt>
                    <dd class="flex space-x-4 text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-2 dark:text-white">
                        <div class="flex-grow">
                            <span x-show="!open">{{ $lastName }}</span>
                            <div x-show="open" style="display: none">
                                <div class="w-full sm:max-w-xs">
                                    <x-shopper-input.group :error="$errors->first('lastName')">
                                        <x-shopper-input.text wire:model.lazy="lastName" id="lastName" type="text" autocomplete="off" />
                                    </x-shopper-input.group>
                                </div>
                            </div>
                        </div>
                        <div class="flex-shrink-0 ml-4">
                            <span x-show="!open">
                                <button @click="open = true" type="button" class="font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400">
                                    {{ __('Update') }}
                                </button>
                            </span>
                            <span x-show="open" class="flex items-start space-x-4" style="display: none">
                                <button wire:click="saveLastName" type="button" class="inline-flex items-center font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400">
                                    <x-shopper-loader wire:loading wire:target="saveLastName" class="text-blue-600" />
                                    <span class="ml-1.5">{{ __('Save') }}</span>
                                </button>
                                <span class="text-gray-300" aria-hidden="true">|</span>
                                <button x-on:click="open = false" type="button" class="font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400">
                                    {{ __('Cancel') }}
                                </button>
                            </span>
                        </div>
                    </dd>
                </div>
                <div class="py-4 space-y-1 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:pt-5">
                    <dt class="text-sm leading-5 font-medium text-gray-500 dark:text-gray-400">
                        {{ __('Photo') }}
                    </dt>
                    <dd class="flex space-x-4 text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-2 dark:text-white">
                    <span class="flex-grow">
                        <img class="h-8 w-8 rounded-full" src="{{ $customer->picture }}" alt="">
                    </span>
                    </dd>
                </div>
                <div x-data="{ open: @entangle('emailUpdate') }" class="py-4 space-y-1 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:pt-5">
                    <dt class="text-sm leading-5 font-medium text-gray-500 dark:text-gray-400">
                        {{ __('Email address') }}
                    </dt>
                    <dd class="flex space-x-4 text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-2 dark:text-white">
                        <div class="flex-grow">
                            <span x-show="!open">{{ $email }}</span>
                            <div x-show="open" style="display: none">
                                <div class="w-full sm:max-w-xs">
                                    <x-shopper-input.group :error="$errors->first('email')">
                                        <x-shopper-input.text wire:model.debounce.350ms="email" id="email" type="text" autocomplete="off" />
                                    </x-shopper-input.group>
                                </div>
                            </div>
                        </div>
                        <div class="flex-shrink-0 ml-4">
                            <span x-show="!open">
                                <button @click="open = true" type="button" class="font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400">
                                    {{ __('Update') }}
                                </button>
                            </span>
                            <span x-show="open" class="flex items-start space-x-4" style="display: none">
                                <button wire:click="saveEmail" type="button" class="inline-flex items-center font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400">
                                    <x-shopper-loader wire:loading wire:target="saveEmail" class="text-blue-600" />
                                    <span class="ml-1.5">{{ __('Save') }}</span>
                                </button>
                                <span class="text-gray-300" aria-hidden="true">|</span>
                                <button x-on:click="$wire.cancelEmail()" type="button" class="font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400">
                                    {{ __('Cancel') }}
                                </button>
                            </span>
                        </div>
                    </dd>
                </div>
                <div x-data="{ open: @entangle('birthDateUpdate') }" x-init="flatpickr($refs.input, {dateFormat: 'Y-m-d'});" class="py-4 space-y-1 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:pt-5">
                    <dt class="text-sm leading-5 font-medium text-gray-500 dark:text-gray-400">
                        {{ __('Birth Date') }}
                    </dt>
                    <dd class="flex space-x-4 text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-2 dark:text-white">
                        <div class="flex-grow">
                            <p x-show="!open" class="flex items-center">
                                <x-heroicon-o-cake class="w-5 h-5 -ml-1 mr-2.5 text-gray-500 dark:text-gray-400" />
                                <span>{{ $birthDateFormatted }}</span>
                            </p>
                            <div x-show="open" style="display: none">
                                <div class="w-full sm:max-w-xs relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <x-heroicon-o-calendar class="h-5 w-5 text-gray-400" />
                                    </div>
                                    <x-shopper-input.text wire:model.lazy="birthDate" x-ref="input" aria-label="{{ __('Birth Date') }}" type="text" class="pl-10" placeholder="1970-01-01" autocomplete="off" readonly />
                                </div>
                            </div>
                        </div>
                        <div class="flex-shrink-0 ml-4">
                            <span x-show="!open">
                                <button @click="open = true" type="button" class="font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400">
                                    {{ __('Update') }}
                                </button>
                            </span>
                            <span x-show="open" class="flex items-start space-x-4" style="display: none">
                                <button wire:click="saveBirthDate" type="button" class="inline-flex items-center font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400">
                                    <x-shopper-loader wire:loading wire:target="saveBirthDate" class="text-blue-600" />
                                    <span class="ml-1.5">{{ __('Save') }}</span>
                                </button>
                                <span class="text-gray-300" aria-hidden="true">|</span>
                                <button @click="open = false" type="button" class="font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400">
                                    {{ __('Cancel') }}
                                </button>
                            </span>
                        </div>
                    </dd>
                </div>
                <div x-data="{ open: @entangle('genderUpdate') }" class="py-4 space-y-1 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:border-b sm:border-gray-200">
                    <dt class="text-sm leading-5 font-medium text-gray-500 dark:text-gray-400">
                        {{ __('Gender') }}
                    </dt>
                    <dd class="flex items-center space-x-4 text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-2 dark:text-white">
                        <div class="flex-grow">
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
                        <div class="flex-shrink-0 ml-4">
                            <span x-show="!open">
                                <button @click="open = true" type="button" class="font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400">
                                    {{ __('Update') }}
                                </button>
                            </span>
                            <span x-show="open" class="flex items-start space-x-4" style="display: none">
                                <button wire:click="saveGender" type="button" class="inline-flex items-center font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400">
                                    <x-shopper-loader wire:loading wire:target="saveGender" class="text-blue-600" />
                                    <span class="ml-1.5">{{ __('Save') }}</span>
                                </button>
                                <span class="text-gray-300" aria-hidden="true">|</span>
                                <button @click="open = false" type="button" class="font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400">
                                    {{ __('Cancel') }}
                                </button>
                            </span>
                        </div>
                    </dd>
                </div>
            </dl>
        </div>
    </div>
    <div class="mt-10 space-y-6 divide-y divide-gray-200 dark:divide-gray-700">
        <div class="space-y-1">
            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                {{ __('Account') }}
            </h3>
            <p class="max-w-2xl text-sm leading-5 text-gray-500 dark:text-gray-400">
                {{ __('Manage how information is used on the customer account.') }}
            </p>
        </div>
        <div>
            <dl class="divide-y divide-gray-200 dark:divide-gray-700">
                <div class="py-4 space-y-1 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:pt-5">
                    <dt class="text-sm leading-5 font-medium text-gray-500 dark:text-gray-400">
                        {{ __('Email Marketing') }}
                    </dt>
                    <dd class="flex text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-2">
                        <span role="checkbox" tabindex="0" x-on:click="on = !on" @keydown.space.prevent="on = !on" :aria-checked="on.toString()" aria-checked="true" x-data="{ on: @entangle('optIn') }" x-bind:class="{ 'bg-gray-200 dark:bg-gray-700': !on, 'bg-blue-600': on }" class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:shadow-outline sm:ml-auto bg-blue-600">
                            <span aria-hidden="true" x-bind:class="{ 'translate-x-5': on, 'translate-x-0': !on }" class="inline-block h-5 w-5 rounded-full bg-white shadow transform transition ease-in-out duration-200 translate-x-5"></span>
                        </span>
                    </dd>
                </div>
                <div class="py-4 space-y-1 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:border-b sm:border-gray-200 sm:dark:border-gray-700">
                    <dt class="text-sm leading-5 font-medium text-gray-500 dark:text-gray-400">
                        {{ __('Two-Factor Authentication') }}
                    </dt>
                    <dd class="flex text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-2 dark:text-white">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold sm:ml-auto rounded-full {{ $hasEnabledTwoFactor ? 'bg-green-100 text-green-800': 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                            {{ $hasEnabledTwoFactor ? __('Enabled') : __('Disabled') }}
                        </span>
                    </dd>
                </div>
            </dl>
        </div>
    </div>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
@endpush
