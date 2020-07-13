@extends('shopper::layouts.'. config('shopper.theme'))
@section('title', __('Create customer'))

@section('content')

    <x:breadcrumb back="shopper.customers.index">
        <a href="{{ route('shopper.customers.index') }}" class="text-gray-500 hover:text-gray-700 focus:outline-none focus:underline transition duration-150 ease-in-out">{{ __('Customers') }}</a>
        <svg class="flex-shrink-0 mx-2 h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
        </svg>
        <span class="text-gray-500">{{ __('Create customer') }}</span>
    </x:breadcrumb>

    <div class="mt-6 md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-500 sm:text-3xl sm:leading-9 sm:truncate pb-4">{{ __('Create customer') }}</h2>
        </div>
    </div>

    {!! Form::open(['route' => 'shopper.customers.store']) !!}
        <div class="hidden sm:block">
            <div class="py-5">
                <div class="border-t border-gray-200"></div>
            </div>
        </div>

        <div class="mt-10 sm:mt-0">
            <div class="md:grid md:grid-cols-3 md:gap-6">
                <div class="md:col-span-1">
                    <div class="px-4 sm:px-0">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">{{ __("Personal Information") }}</h3>
                        <p class="mt-1 text-sm leading-5 text-gray-600">
                            {{ __("Use a permanent address where you can receive mail.") }}
                        </p>
                    </div>
                </div>
                <div class="mt-5 md:mt-0 md:col-span-2">
                    <div class="bg-white shadow sm:rounded-md">
                        <div class="px-4 py-5 sm:p-6">
                            <div class="grid grid-cols-6 gap-6">
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="first_name" class="block text-sm font-medium leading-5 text-gray-700">{{ __("First name") }}</label>
                                    <div class="mt-1 relative">
                                        {!! Form::text('first_name', null, [
                                                'class' => 'form-input block w-full sm:text-sm sm:leading-5 transition duration-150 ease-in-out',
                                                'id' => 'first_name',
                                                'autocomplete' => 'off',
                                                'required' => true
                                            ])
                                        !!}
                                        @error('first_name')
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        @enderror
                                    </div>
                                    @error('first_name')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-span-6 sm:col-span-3">
                                    <label for="last_name" class="block text-sm font-medium leading-5 text-gray-700">{{ __("Last name") }}</label>
                                    <div class="mt-1 relative">
                                        {!! Form::text('last_name', null, [
                                                'class' => 'form-input block w-full sm:text-sm sm:leading-5 transition duration-150 ease-in-out',
                                                'id' => 'last_name',
                                                'autocomplete' => 'off',
                                                'required' => true
                                            ])
                                        !!}
                                        @error('last_name')
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        @enderror
                                    </div>
                                    @error('last_name')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-span-6">
                                    <label for="email" class="block text-sm font-medium leading-5 text-gray-700">{{ __("Email address") }}</label>
                                    <div class="mt-1 relative">
                                        {!! Form::text('email', null, [
                                                'class' => 'form-input block w-full sm:text-sm sm:leading-5 transition duration-150 ease-in-out pr-10',
                                                'id' => 'email',
                                                'autocomplete' => 'off',
                                                'required' => true
                                            ])
                                        !!}
                                        @error('email')
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        @enderror
                                    </div>
                                    @error('email')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-span-6">
                                    <label for="phone_number" class="block text-sm font-medium leading-5 text-gray-700">{{ __("Phone number") }}</label>
                                    <div class="mt-1 relative">
                                        {!! Form::tel('phone_number', null, [
                                                'class' => 'form-input block w-full sm:text-sm sm:leading-5 transition duration-150 ease-in-out pr-10',
                                                'id' => 'phone_number',
                                                'autocomplete' => 'off'
                                            ])
                                        !!}
                                        @error('phone_number')
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        @enderror
                                    </div>
                                    @error('phone_number')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
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
                        <h3 class="text-lg font-medium leading-6 text-gray-900">{{ __("Security") }}</h3>
                        <p class="mt-1 text-sm leading-5 text-gray-600">
                            {{ __("Enter a random password that this user will use to login to his account.") }}
                        </p>
                    </div>
                </div>
                <div class="mt-5 md:mt-0 md:col-span-2">
                    <div class="bg-white shadow sm:rounded-md">
                        <div class="px-4 py-5 sm:p-6">
                            <div class="grid grid-cols-6 gap-6">
                                <div class="col-span-6">
                                    <label for="password" class="block text-sm font-medium leading-5 text-gray-700">{{ __("Password") }}</label>
                                    <div class="mt-1 relative">
                                        {!! Form::password('password', [
                                                'class' => 'form-input block w-full sm:text-sm sm:leading-5 transition duration-150 ease-in-out',
                                                'id' => 'password',
                                                'autocomplete' => 'off',
                                                'required' => true
                                            ])
                                        !!}
                                        @error('password')
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        @enderror
                                    </div>
                                    @error('password')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-span-6">
                                    <label for="password_confirmation" class="block text-sm font-medium leading-5 text-gray-700">{{ __("Password Confirmation") }}</label>
                                    <div class="mt-1 relative">
                                        {!! Form::password('password_confirmation', [
                                                'class' => 'form-input block w-full sm:text-sm sm:leading-5 transition duration-150 ease-in-out',
                                                'id' => 'password_confirmation',
                                                'autocomplete' => 'off',
                                                'required' => true
                                            ])
                                        !!}
                                        @error('password')
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        @enderror
                                    </div>
                                    @error('password')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 border-t pt-5 border-gray-200">
            <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-brand-500 hover:bg-brand-600 focus:outline-none focus:border-brand-700 focus:shadow-outline-brand active:bg-brand-700 transition ease-in-out duration-150">{{ __('Save') }}</button>
            </div>
        </div>
    {!! Form::close() !!}

@endsection
