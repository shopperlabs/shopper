@extends('shopper::auth.layout')
@section('title', __('Reset Password'))

@section('content')

    <div x-data="{ recovery: false }" class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full px-6 space-y-4">
            @if($errors->any())
                <div class="rounded-md bg-red-50 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm leading-5 font-medium text-red-800">
                                {{ __("Your submission contains errors. Please try again") }}
                            </h3>
                            <div class="mt-2 text-sm leading-5 text-red-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    @foreach ($errors->all() as $message)
                                        <li>{{ $message }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="flex-shrink-0">
                <img class="mx-auto h-20 w-auto" src="{{ asset('shopper/images/logo.svg') }}" alt="Laravel Shopper">
            </div>
            <div class="bg-white rounded-lg shadow-md overflow-hidden p-4 sm:p-6">
                <div class="text-center">
                    <h2 class="inline-flex items-center text-xl font-semibold font-heading text-center leading-9 text-gray-800">
                        <svg class="w-10 h-10 text-blue-600 -ml-1 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        {{ __('Authenticate Your Account') }}
                    </h2>
                    <p class="mt-1 text-sm leading-5 text-center text-gray-500" x-show="! recovery">
                        {{ __("Please confirm access to your account by entering the authentication code provided by your authenticator application.") }}
                    </p>
                    <p class="mt-1 text-sm leading-5 text-center text-gray-500" x-show="recovery" style="display: none">
                        {{ __("Please confirm access to your account by entering one of your emergency recovery codes.") }}
                    </p>
                </div>
                <form class="mt-5" action="{{ route('shopper.two-factor.post-login') }}" method="POST">
                    @csrf
                    <x-shopper-input.group x-show="! recovery" label="Code" for="code">
                        <input  id="code" type="text" name="code" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" autofocus x-ref="code" autocomplete="one-time-code" />
                    </x-shopper-input.group>

                    <x-shopper-input.group x-show="recovery" label="Recovery Code" for="recovery_code" style="display: none">
                        <input  id="recovery_code" name="recovery_code" type="text" class="form-input block w-full transition duration-150 ease-in-out sm:text-sm sm:leading-5" x-ref="recovery_code" autocomplete="one-time-code" />
                    </x-shopper-input.group>

                    <div class="mt-5 flex items-center space-x-4">
                        <p class="text-sm leading-5 text-gray-500">
                            {{ __("Can't remember this code?") }}
                            <button
                                class="ml-1 text-sm text-gray-600 hover:text-gray-900 underline cursor-pointer"
                                type="button"
                                x-show="! recovery"
                                x-on:click="
                                    recovery = true;
                                    $nextTick(() => { $refs.recovery_code.focus() })
                                "
                            >
                                {{ __('Use a recovery code') }}
                            </button>

                            <button
                                x-show="recovery"
                                type="button"
                                class="ml-1 text-sm text-gray-600 hover:text-gray-900 underline cursor-pointer"
                                style="display: none"
                                x-on:click="
                                    recovery = false;
                                    $nextTick(() => { $refs.code.focus() })
                                "
                            >
                                {{ __('Use an authentication code') }}
                            </button>
                        </p>
                        <button type="submit" class="relative inline-flex items-center py-2 px-4 border border-transparent rounded-md text-white font-semibold bg-blue-600 hover:bg-blue-700 focus:bg-blue-800 focus:outline-none focus:shadow-outline-blue sm:text-sm sm:leading-5">
                            {{ __('Login') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
