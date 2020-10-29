@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/intl-tel-input@17.0.3/build/css/intlTelInput.min.css">
@endpush

<div class="mt-8">
    <form wire:submit.prevent="save">
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="md:col-span-1">
                <div class="px-4 sm:px-0">
                    <h3 class="text-lg font-semibold leading-6 text-gray-900">{{ __("Profile Information") }}</h3>
                    <p class="mt-4 text-sm leading-5 text-gray-600">
                        {{ __("Update your account's profile information and email address.") }}
                    </p>
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-2">
                <div class="shadow rounded-md">
                    <div class="px-4 py-5 bg-white sm:p-6 rounded-t-md">
                        <div>
                            <label class="block text-sm leading-5 font-medium text-gray-700">Photo</label>
                            <div class="mt-2 flex items-center">
                                <span class="inline-block h-12 w-12 rounded-full overflow-hidden bg-gray-100">
                                    @if($picture)
                                        <img class="h-full w-full bg-center" src="{{ $picture->temporaryUrl() }}" alt="">
                                    @else
                                        <img class="h-full w-full bg-center" src="{{ $logged_in_user->picture }}" alt="{{ $logged_in_user->email }}">
                                    @endif
                                </span>
                                <span class="ml-5 rounded-md shadow-sm">
                                    <label class="inline-flex cursor-pointer py-2 px-3 border border-gray-300 rounded-md text-sm leading-4 font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:border-brand-400 focus:shadow-outline-brand active:bg-gray-50 active:text-gray-800 transition duration-150 ease-in-out">
                                        {{ __("Change") }}
                                        <input type='file' wire:model='picture' class='hidden' />
                                    </label>
                                </span>
                            </div>
                        </div>
                        <div class="grid gap-4 grid-cols-6 sm:gap-5 mt-5">
                            <div class="col-span-6 sm:col-span-3">
                                <label for="first_name" class="block text-sm font-medium leading-5 text-gray-700">{{ __("First name") }}</label>
                                <div class="relative mt-1">
                                    <input type='text' wire:model='first_name'  class='form-input block w-full sm:text-sm sm:leading-5' autocomplete='off' id='first_name' />
                                </div>
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="last_name" class="block text-sm font-medium leading-5 text-gray-700">{{ __("Last name") }}</label>
                                <div class="relative mt-1">
                                    <input type='text' wire:model='last_name'  class='form-input block w-full sm:text-sm sm:leading-5' autocomplete='off' id='last_name' />
                                </div>
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="email" class="block text-sm font-medium leading-5 text-gray-700">{{ __("Email address") }}</label>
                                <div class="relative mt-1">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                        </svg>
                                    </div>
                                    <input wire:model='email' id='email' type='email' class='form-input block pl-10 w-full sm:text-sm sm:leading-5' autocomplete='off' />
                                </div>
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
                                class="col-span-6 sm:col-span-3"
                            >
                                <div class="flex items-center justify-between">
                                    <label for="phone_number" class="block text-sm font-medium leading-5 text-gray-700">{{ __("Phone number") }}</label>
                                    <span class="text-gray-500 text-sm leading-5">Optional</span>
                                </div>
                                <div class="mt-1 relative">
                                    <input wire:model="phone_number" id="phone_number" type="tel" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5 pr-10" autocomplete="off" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6 rounded-b-md">
                        <span class="inline-flex rounded-md shadow-sm">
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue active:bg-blue-700 transition duration-150 ease-in-out">
                                <svg wire:loading wire:target="save" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                                </svg>
                                {{ __("Save") }}
                            </button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://unpkg.com/intl-tel-input@17.0.3/build/js/intlTelInput.min.js"></script>
@endpush