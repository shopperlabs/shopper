@extends('shopper::auth.layout')
@section('title', __('Reset Password'))

@section('content')

    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full px-6">
            @if(session()->has('success'))
                <div class="rounded-md bg-green-100 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3 flex-1 md:flex md:justify-between">
                            <p class="text-sm leading-5 text-green-700">
                                {{ session()->get('success') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="mt-4">
                <img class="mx-auto h-20 w-auto" src="{{ asset('shopper/images/logo.svg') }}" alt="Laravel Shopper">
                <h2 class="mt-10 text-3xl font-semibold text-center leading-9 text-gray-800">{{ __('Reset your password') }}</h2>
                <p class="mt-5 text-sm leading-5 text-center text-gray-600">
                    {{ __("Enter the email address you used when creating your account and we will send you instructions to reset your password.") }}
                </p>
            </div>

            <form class="mt-5" action="{{ route('shopper.password.email') }}" method="POST">
                @csrf
                <div class="rounded-md shadow-sm">
                    <input
                        aria-label="{{ __("Email address") }}"
                        name="email"
                        type="email"
                        required
                        autofocus
                        class="border-gray-300 placeholder-gray-500 focus:shadow-outline-blue focus:border-blue-300  appearance-none relative block w-full px-3 py-2 border text-gray-900 rounded-md focus:outline-none sm:text-sm sm:leading-5"
                        placeholder="{{ __("Email address") }}"
                        value="{{ old('email') }}"
                    >
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-5">
                    <button type="submit" class="relative block w-full py-2 px-3 border border-transparent rounded-md text-white font-semibold bg-blue-600 hover:bg-blue-700 focus:bg-blue-800 focus:outline-none focus:shadow-outline-blue sm:text-sm sm:leading-5">
                        {{ __('Send password reset mail') }}
                    </button>
                </div>
                <p class="mt-5 text-center text-sm">
                    <a href="{{ route('shopper.login-view') }}" class="inline-flex items-center text-gray-600 hover:text-gray-500 leading-5 transition duration-150 ease-in-out">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" class="arrow-narrow-left w-5 h-5 mr-1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18"/>
                        </svg>
                        {{ __("Return to login page") }}
                    </a>
                </p>
            </form>
        </div>
    </div>

@stop
