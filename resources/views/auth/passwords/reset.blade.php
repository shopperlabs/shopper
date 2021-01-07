@extends('shopper::auth.layout')
@section('title', __('Reset Password'))

@section('content')

    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full px-6">
            <div class="space-y-4">
                <x-shopper-validation-errors />
                <div>
                    <x-shopper-application-logo class="mx-auto h-20 w-auto" />
                    <h2 class="mt-10 text-3xl font-semibold font-heading text-center leading-9 text-gray-800">{{ __('Reset your password') }}</h2>
                    <p class="mt-5 text-sm leading-5 text-center text-gray-600">
                        {{ __("Enter your email and the new password you'd like to use to access your account.") }}
                    </p>
                </div>
            </div>
            <form class="mt-5" action="{{ route('shopper.password.update') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <div class="rounded-md shadow-sm">
                    <div>
                        <input aria-label="{{ __('Email address') }}" value="{{ $email ?? old('email') }}" name="email" type="email" autocomplete="off" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-600 rounded-t-md focus:outline-none focus:shadow-outline-blue focus:border-blue-300 focus:z-10 sm:text-sm sm:leading-5" placeholder="{{ __("Email address") }}">
                    </div>
                    <div class="-mt-px">
                        <input aria-label="{{ __('Password') }}" name="password" type="password" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-600 rounded-b-md focus:outline-none focus:shadow-outline-blue focus:border-blue-300 focus:z-10 sm:text-sm sm:leading-5" placeholder="{{ __("New password") }}">
                    </div>
                </div>

                <div class="mt-5">
                    <button type="submit" class="relative block w-full py-2 px-3 border border-transparent rounded-md text-white font-semibold bg-blue-600 hover:bg-blue-700 focus:bg-blue-800 focus:outline-none focus:shadow-outline-blue sm:text-sm sm:leading-5">
                        {{ __('Update password') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

@stop
