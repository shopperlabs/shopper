@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

<div class="bg-white shadow overflow-hidden rounded-md px-4 py-5 bg-white sm:p-6">
    <div class="space-y-6 divide-y divide-gray-200">
        <div class="space-y-1">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                {{ __("Profile") }}
            </h3>
            <p class="max-w-2xl text-sm leading-5 text-gray-500">
                {{ __("All your customer's public information can be found here.") }}
            </p>
        </div>
        <div>
            <dl class="divide-y divide-gray-200">
                <div x-data="{ open: @entangle('firstNameUpdate') }" class="py-4 space-y-1 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4">
                    <dt class="text-sm leading-5 font-medium text-gray-500">
                        {{ __("First name") }}
                    </dt>
                    <dd class="flex space-x-4 text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-2">
                        <div class="flex-grow">
                            <span x-show="!open">{{ $firstName }}</span>
                            <div x-show="open" style="display: none">
                                <div class="w-full sm:max-w-xs">
                                    <x-shopper-input.group :error="$errors->first('firstName')">
                                        <input wire:model="firstName" id="firstName" type="text" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" autocomplete="off" />
                                    </x-shopper-input.group>
                                </div>
                            </div>
                        </div>
                        <div class="flex-shrink-0 ml-4">
                            <span x-show="!open">
                                <button @click="open = true" type="button" class="font-medium text-blue-600 hover:text-blue-500 transition duration-150 ease-in-out">
                                    {{ __("Update") }}
                                </button>
                            </span>
                            <span x-show="open" class="flex items-start space-x-4" style="display: none">
                                <button wire:click="saveFirstName" type="button" class="inline-flex items-center font-medium text-blue-600 hover:text-blue-500 transition duration-150 ease-in-out">
                                    <svg wire:loading wire:target="saveFirstName" class="animate-spin h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                                    </svg>
                                    <span class="ml-1.5">{{ __("Save") }}</span>
                                </button>
                                <span class="text-gray-300" aria-hidden="true">|</span>
                                <button x-on:click="open = false" type="button" class="font-medium text-blue-600 hover:text-blue-500 transition duration-150 ease-in-out">
                                    {{ __("Cancel") }}
                                </button>
                            </span>
                        </div>
                    </dd>
                </div>
                <div x-data="{ open: @entangle('lastNameUpdate') }" class="py-4 space-y-1 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:pt-5">
                    <dt class="text-sm leading-5 font-medium text-gray-500">
                        {{ __("Last name") }}
                    </dt>
                    <dd class="flex space-x-4 text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-2">
                        <div class="flex-grow">
                            <span x-show="!open">{{ $lastName }}</span>
                            <div x-show="open" style="display: none">
                                <div class="w-full sm:max-w-xs">
                                    <x-shopper-input.group :error="$errors->first('lastName')">
                                        <input wire:model="lastName" id="lastName" type="text" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" autocomplete="off" />
                                    </x-shopper-input.group>
                                </div>
                            </div>
                        </div>
                        <div class="flex-shrink-0 ml-4">
                            <span x-show="!open">
                                <button @click="open = true" type="button" class="font-medium text-blue-600 hover:text-blue-500 transition duration-150 ease-in-out">
                                    {{ __("Update") }}
                                </button>
                            </span>
                            <span x-show="open" class="flex items-start space-x-4" style="display: none">
                                <button wire:click="saveLastName" type="button" class="inline-flex items-center font-medium text-blue-600 hover:text-blue-500 transition duration-150 ease-in-out">
                                    <svg wire:loading wire:target="saveLastName" class="animate-spin h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                                    </svg>
                                    <span class="ml-1.5">{{ __("Save") }}</span>
                                </button>
                                <span class="text-gray-300" aria-hidden="true">|</span>
                                <button x-on:click="open = false" type="button" class="font-medium text-blue-600 hover:text-blue-500 transition duration-150 ease-in-out">
                                    {{ __("Cancel") }}
                                </button>
                            </span>
                        </div>
                    </dd>
                </div>
                <div class="py-4 space-y-1 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:pt-5">
                    <dt class="text-sm leading-5 font-medium text-gray-500">
                        {{ __("Photo") }}
                    </dt>
                    <dd class="flex space-x-4 text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-2">
                    <span class="flex-grow">
                        <img class="h-8 w-8 rounded-full" src="{{ $customer->picture }}" alt="">
                    </span>
                    </dd>
                </div>
                <div x-data="{ open: @entangle('emailUpdate') }" class="py-4 space-y-1 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:pt-5">
                    <dt class="text-sm leading-5 font-medium text-gray-500">
                        {{ __("Email address") }}
                    </dt>
                    <dd class="flex space-x-4 text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-2">
                        <div class="flex-grow">
                            <span x-show="!open">{{ $email }}</span>
                            <div x-show="open" style="display: none">
                                <div class="w-full sm:max-w-xs">
                                    <x-shopper-input.group :error="$errors->first('email')">
                                        <input wire:model="email" id="email" type="text" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" autocomplete="off" />
                                    </x-shopper-input.group>
                                </div>
                            </div>
                        </div>
                        <div class="flex-shrink-0 ml-4">
                            <span x-show="!open">
                                <button @click="open = true" type="button" class="font-medium text-blue-600 hover:text-blue-500 transition duration-150 ease-in-out">
                                    {{ __("Update") }}
                                </button>
                            </span>
                            <span x-show="open" class="flex items-start space-x-4" style="display: none">
                                <button wire:click="saveEmail" type="button" class="inline-flex items-center font-medium text-blue-600 hover:text-blue-500 transition duration-150 ease-in-out">
                                    <svg wire:loading wire:target="saveEmail" class="animate-spin h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                                    </svg>
                                    <span class="ml-1.5">{{ __("Save") }}</span>
                                </button>
                                <span class="text-gray-300" aria-hidden="true">|</span>
                                <button x-on:click="$wire.cancelEmail()" type="button" class="font-medium text-blue-600 hover:text-blue-500 transition duration-150 ease-in-out">
                                    {{ __("Cancel") }}
                                </button>
                            </span>
                        </div>
                    </dd>
                </div>
                <div x-data="{ open: @entangle('birthDateUpdate') }" x-init="flatpickr($refs.input, {dateFormat: 'Y-m-d'});" class="py-4 space-y-1 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:pt-5">
                    <dt class="text-sm leading-5 font-medium text-gray-500">
                        {{ __("Birth Date") }}
                    </dt>
                    <dd class="flex space-x-4 text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-2">
                        <div class="flex-grow">
                            <p x-show="!open" class="flex items-center">
                                <svg class="w-5 h-5 -ml-1 mr-2.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.701 2.701 0 00-1.5-.454M9 6v2m3-2v2m3-2v2M9 3h.01M12 3h.01M15 3h.01M21 21v-7a2 2 0 00-2-2H5a2 2 0 00-2 2v7h18zm-3-9v-2a2 2 0 00-2-2H8a2 2 0 00-2 2v2h12z" />
                                </svg>
                                <span>{{ $birthDateFormatted }}</span>
                            </p>
                            <div x-show="open" style="display: none">
                                <div class="w-full sm:max-w-xs relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <input wire:model="birthDate" x-ref="input" aria-label="{{ __("Birth Date") }}" type="text" class="form-input block w-full pl-10 sm:text-sm sm:leading-5" placeholder="1970-01-01" autocomplete="off" readonly />
                                </div>
                            </div>
                        </div>
                        <div class="flex-shrink-0 ml-4">
                            <span x-show="!open">
                                <button @click="open = true" type="button" class="font-medium text-blue-600 hover:text-blue-500 transition duration-150 ease-in-out">
                                    {{ __("Update") }}
                                </button>
                            </span>
                            <span x-show="open" class="flex items-start space-x-4" style="display: none">
                                <button wire:click="saveBirthDate" type="button" class="inline-flex items-center font-medium text-blue-600 hover:text-blue-500 transition duration-150 ease-in-out">
                                    <svg wire:loading wire:target="saveBirthDate" class="animate-spin h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                                    </svg>
                                    <span class="ml-1.5">{{ __("Save") }}</span>
                                </button>
                                <span class="text-gray-300" aria-hidden="true">|</span>
                                <button @click="open = false" type="button" class="font-medium text-blue-600 hover:text-blue-500 transition duration-150 ease-in-out">
                                    {{ __("Cancel") }}
                                </button>
                            </span>
                        </div>
                    </dd>
                </div>
                <div x-data="{ open: @entangle('genderUpdate') }" class="py-4 space-y-1 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:border-b sm:border-gray-200">
                    <dt class="text-sm leading-5 font-medium text-gray-500">
                        {{ __("Gender") }}
                    </dt>
                    <dd class="flex items-center space-x-4 text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-2">
                        <div class="flex-grow">
                            <span x-show="!open" class="capitalize">{{ $gender }}</span>
                            <div x-show="open" style="display: none">
                                <div class="w-full sm:max-w-xs">
                                    <select wire:model="gender" aria-label="{{ __("Gender") }}" class="form-select block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5">
                                        <option value="male">{{ __("Male") }}</option>
                                        <option value="female">{{ __("Female") }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="flex-shrink-0 ml-4">
                            <span x-show="!open">
                                <button @click="open = true" type="button" class="font-medium text-blue-600 hover:text-blue-500 transition duration-150 ease-in-out">
                                    {{ __("Update") }}
                                </button>
                            </span>
                            <span x-show="open" class="flex items-start space-x-4" style="display: none">
                                <button wire:click="saveGender" type="button" class="inline-flex items-center font-medium text-blue-600 hover:text-blue-500 transition duration-150 ease-in-out">
                                    <svg wire:loading wire:target="saveGender" class="animate-spin h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                                    </svg>
                                    <span class="ml-1.5">{{ __("Save") }}</span>
                                </button>
                                <span class="text-gray-300" aria-hidden="true">|</span>
                                <button @click="open = false" type="button" class="font-medium text-blue-600 hover:text-blue-500 transition duration-150 ease-in-out">
                                    {{ __("Cancel") }}
                                </button>
                            </span>
                        </div>
                    </dd>
                </div>
            </dl>
        </div>
    </div>
    <div class="mt-10 space-y-6 divide-y divide-gray-200">
        <div class="space-y-1">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                {{ __("Account") }}
            </h3>
            <p class="max-w-2xl text-sm leading-5 text-gray-500">
                {{ __("Manage how information is used on the customer account.") }}
            </p>
        </div>
        <div>
            <dl class="divide-y divide-gray-200">
                <div class="py-4 space-y-1 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:pt-5">
                    <dt class="text-sm leading-5 font-medium text-gray-500">
                        {{ __("Email Marketing") }}
                    </dt>
                    <dd class="flex text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-2">
                        <span role="checkbox" tabindex="0" x-on:click="on = !on" @keydown.space.prevent="on = !on" :aria-checked="on.toString()" aria-checked="true" x-data="{ on: @entangle('optIn') }" x-bind:class="{ 'bg-gray-200': !on, 'bg-blue-600': on }" class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:shadow-outline sm:ml-auto bg-blue-600">
                            <span aria-hidden="true" x-bind:class="{ 'translate-x-5': on, 'translate-x-0': !on }" class="inline-block h-5 w-5 rounded-full bg-white shadow transform transition ease-in-out duration-200 translate-x-5"></span>
                        </span>
                    </dd>
                </div>
                <div class="py-4 space-y-1 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:border-b sm:border-gray-200">
                    <dt class="text-sm leading-5 font-medium text-gray-500">
                        {{ __("Two-Factor Authentication") }}
                    </dt>
                    <dd class="flex text-sm leading-5 text-gray-900 sm:mt-0 sm:col-span-2">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold sm:ml-auto rounded-full {{ $hasEnabledTwoFactor ? 'bg-green-100 text-green-800': 'bg-gray-100 text-gray-800' }}">
                            {{ $hasEnabledTwoFactor ? __("Enabled") : __("Disabled") }}
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
