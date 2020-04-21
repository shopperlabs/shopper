<div class="py-8">

    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-medium leading-6 text-gray-900">{{ __("Password") }}</h3>
                <p class="mt-4 text-sm leading-5 text-gray-600">
                    {{ __("Update your password to protect your account.") }}
                </p>
            </div>
        </div>
        <div class="mt-5 md:mt-0 md:col-span-2">
            {!! Form::open([]) !!}
                <div class="shadow sm:rounded-md sm:overflow-hidden">
                    <div class="px-4 py-5 bg-white sm:p-6">
                        <div class="grid grid-cols-6 gap-6">
                            <div class="sm:col-span-6">
                                <label for="current_password" class="block text-sm font-medium leading-5 text-gray-700">{{ __("Current password") }}</label>
                                <div class="mt-1 rounded-md shadow-sm">
                                    {!! Form::password('current_password', [
                                            'class' => 'mt-1 form-input block w-full sm:text-sm sm:leading-5',
                                            'id' => 'current_password',
                                            'autocomplete' => 'off'
                                        ])
                                    !!}
                                </div>
                            </div>

                            <div class="sm:col-span-6">
                                <label for="password" class="block text-sm font-medium leading-5 text-gray-700">{{ __("New password") }}</label>
                                <div class="mt-1 rounded-md shadow-sm">
                                    {!! Form::password('password', [
                                            'class' => 'mt-1 form-input block w-full sm:text-sm sm:leading-5',
                                            'id' => 'password',
                                            'autocomplete' => 'off'
                                        ])
                                    !!}
                                </div>
                                <p class="text-sm font-italic text-gray-400 mt-1">{{ __("Your password must be more than 6 characters long and must contain at least 1 uppercase, 1 lowercase and 1 digit.") }}</p>
                            </div>

                            <div class="sm:col-span-6">
                                <label for="password_confirmation" class="block text-sm font-medium leading-5 text-gray-700">{{ __("Confirme new password") }}</label>
                                <div class="mt-1 rounded-md shadow-sm">
                                    {!! Form::password('password_confirmation', [
                                            'class' => 'mt-1 form-input block w-full sm:text-sm sm:leading-5',
                                            'id' => 'password_confirmation',
                                            'autocomplete' => 'off'
                                        ])
                                    !!}
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                        <span class="inline-flex rounded-md shadow-sm">
                            <button class="btn btn-primary">
                                {{ __("Update Password") }}
                            </button>
                        </span>
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
    </div>

    <div class="hidden sm:block">
        <div class="py-5">
            <div class="border-t border-gray-200"></div>
        </div>
    </div>

    <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-medium leading-6 text-gray-900">{{ __("Two Factor Authentication") }}</h3>
                <p class="mt-4 text-sm leading-5 text-gray-600">
                    {{ __("After entering your password, verify your identity with a second authentication method.") }}
                </p>
            </div>
        </div>
        <div class="mt-5 md:mt-0 md:col-span-2">
            {!! Form::open([]) !!}
            <div class="shadow sm:rounded-md sm:overflow-hidden">
                <div class="px-4 py-5 bg-white sm:p-6">
                    <div class="rounded-md bg-blue-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3 flex-1 md:flex md:justify-between">
                                <p class="text-sm leading-5 text-blue-700">
                                    {{ __("To utilize two factor authentication, you must install the Google Authenticator application on your smartphone.") }}
                                </p>
                                <p class="mt-3 text-sm leading-5 md:mt-0 md:ml-6">
                                    <a href="https://support.google.com/accounts/answer/1066447" target="_blank" class="whitespace-no-wrap font-medium text-blue-700 hover:text-blue-600 transition ease-in-out duration-150">
                                        {{ __("Details") }} &rarr;
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <span class="block w-12 w-12">
                                    <svg class="w-full h-full text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                </span>
                            </div>
                            <p class="ml-4 text-gray-500 text-sm">
                                {{ __("With two factor authentication, only you can access your account — even if someone else has your password.") }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                    <span class="inline-flex rounded-md shadow-sm">
                        <button class="btn btn-primary">
                            {{ __("Enable Authentication") }}
                        </button>
                    </span>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>

</div>

