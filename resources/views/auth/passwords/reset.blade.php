@extends('shopper::auth.layout')
@section('title', __('Reset Password'))

@section('content')

    <div class="flex justify-between items-center">
        <a href="{{ route('shopper.login-view') }}" class="flex items-center">
            <svg class="h-6 w-6 mr-2 text-gray-700" xmlns="http://www.w3.org/2000/svg">
                <path d="M5.41 11H21a1 1 0 110 2H5.41l5.3 5.3a1 1 0 01-1.42 1.4l-7-7a1 1 0 010-1.4l7-7a1 1 0 011.42 1.4L5.4 11h.01z" fill="currentColor" />
            </svg>
            <span>{{ __('Back to Login') }}</span>
        </a>
    </div>
    <div class="w-full md:w-3/4 lg:w-125 mb-16 mx-auto">
        <h1 class="text-2xl font-medium text-gray-700 mb-4">{{ __('Reset Password') }}</h1>
        <div class="p-8 bg-white shadow-smooth rounded-md">
            <form method="POST" class="reset-form" novalidate action="{{ route('shopper.password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="w-full mb-3">
                    <label for="email" class="block text-sm leading-5 font-medium text-gray-700 mb-2">{{ __('E-Mail Address') }}</label>
                    <input id="email" type="email" class="form-input" name="email" required autofocus>
                </div>
                <div class="w-full mb-3">
                    <label for="password" class="block text-sm leading-5 font-medium text-gray-700 mb-2">{{ __('Password') }}</label>
                    <input id="password" type="password" class="form-input" name="password" required>
                </div>
                <div class="w-full mb-3">
                    <label for="confirm_password" class="block text-sm leading-5 font-medium text-gray-700 mb-2">{{ __('Confirm Password') }}</label>
                    <input id="confirm_password" type="password" class="form-input" name="confirm_password" required>
                </div>
                <button type="submit" class="group relative py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-brand hover:bg-brand-hover focus:outline-none focus:border-brand-hover active:bg-brand-hover transition duration-150 ease-in-out">
                    {{ __('Reset my password') }}
                </button>
            </form>
        </div>
    </div>

@stop
